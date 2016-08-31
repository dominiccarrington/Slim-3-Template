<?php
namespace App\Command;

use App\App;
use Phinx\Util\Util as PhinxUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CrudCommand extends Command
{
    private $currentNamespace;
    private $controllerStub;
    private $modelStub;
    private $routeStub;

    protected function configure()
    {
        $this
            ->setName('crud:create')
            ->setDescription('Create CRUD files')
            ->addArgument('model name', InputArgument::REQUIRED, "The name of the model to be created");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $model = $input->getArgument('model name');

        if (PhinxUtil::isValidMigrationFileName($model) > 0) {
            throw new \InvalidArgumentException(sprintf(
                'The migration class name "%s" is invalid. Please use CamelCase format.',
                $model
            ));
        }

        $this->currentNamespace = App::getCurrentAppNamespace();
        $this->checkIfStubsExist();
        $this->createController($model, $output);
        $this->createMigration($model, $output);
        $this->createModel($model, $output);
        $this->createRoutes($model, $output);
    }

    private function checkIfStubsExist()
    {
        if (file_exists(STUB_DIR . '/crud_controller.stub')) {
            $this->controllerStub = file_get_contents(STUB_DIR . '/crud_controller.stub');
        } else {
            throw new \RuntimeException("Controller stub (crud_controller.stub) should be found in " . STUB_DIR . ", it is missing.");
        }

        if (file_exists(STUB_DIR . '/model.stub')) {
            $this->modelStub = file_get_contents(STUB_DIR . '/model.stub');
        } else {
            throw new \RuntimeException("Model stub (model.stub) should be found in " . STUB_DIR . ", it is missing.");
        }

        if (file_exists(STUB_DIR . '/crud_routes.stub')) {
            $this->routeStub = file_get_contents(STUB_DIR . '/crud_routes.stub');
        } else {
            throw new \RuntimeException("Routes stub (crud_routes.stub) should be found in " . STUB_DIR . ", it is missing.");            
        }
    }

    private function createController($model, OutputInterface $output)
    {
        file_put_contents(
            APP_DIR . "/Controller/{$model}Controller.php", 
            $this->convertString($model, $this->controllerStub)
        );
    }

    private function createMigration($model, OutputInterface $output)
    {
        $command = $this->getApplication()->find('db:create');

        $args = [
            "name" => $model,
        ];

        $greetInput = new ArrayInput($args);
        $returnCode = $command->run($greetInput, $output);
    }

    private function createModel($model, OutputInterface $output)
    {
        file_put_contents(
            APP_DIR . "/Model/{$model}.php",
            $this->convertString($model, $this->modelStub)
        );
    }

    private function createRoutes($model, OutputInterface $output)
    {
        file_put_contents(
            CONFIG_DIR . "/routes.php",
            $this->convertString($model, $this->routeStub),
            FILE_APPEND
        );
    }

    private function convertString($model, $text)
    {
        $conversion = [
            "[controllerName]" => "{$model}Controller",
            "[modelName]" => $model,
            "[modelVar]" => strtolower($model),
            "[namespace]" => $this->currentNamespace,
            "[fullController]" => $this->currentNamespace . "\\Controller\\" . $model . "Controller",
        ];
        $string = str_replace(array_keys($conversion), array_values($conversion), $text);

        return $string;
    }
}
