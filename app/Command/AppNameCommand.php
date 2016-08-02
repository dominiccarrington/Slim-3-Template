<?php
namespace App\Command;

use FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppNameCommand extends Command
{
    private $current;

    protected function configure()
    {
        $this
            ->setName("app:name")
            ->setDescription("Change the namespace of the application")
            ->addArgument("name", InputArgument::REQUIRED, "The new name of the application")
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getCurrentNamespace();
        $this->changeAppNamespace($input, $output);
        $this->changeBootstrap($input, $output);
        $this->changeConfig($input, $output);
        $this->changeRoutes($input, $output);
        $this->updateComposer($input, $output);
    }

    private function getCurrentNamespace()
    {
        preg_match("/namespace (\w*);/", explode(PHP_EOL, file_get_contents(APP_DIR . "/App.php"))[1], $results);
        $this->current = $results[1];
    }

    private function changeAppNamespace(InputInterface $input, OutputInterface $output)
    {
        $this->foreachFolder(APP_DIR, ["php"], function ($file) use ($input, $output) {
            $search = [
                'namespace ' . $this->current . ';',
                $this->current . '\\',
            ];

            $replace = [
                'namespace ' . $input->getArgument('name') . ';',
                $input->getArgument('name') . '\\',
            ];

            $this->replaceIn($file, $search, $replace);
        });
    }

    private function changeBootstrap(InputInterface $input, OutputInterface $output)
    {
        //NO-OP
    }
    
    private function changeConfig(InputInterface $input, OutputInterface $output)
    {
        $this->foreachFolder(CONFIG_DIR, ["php"], function ($file) use ($input, $output) {
            $search = [
                $this->current . '\\',
            ];

            $replace = [
                $input->getArgument('name') . '\\',
            ];

            $this->replaceIn($file, $search, $replace);
        });
    }
    
    private function changeRoutes(InputInterface $input, OutputInterface $output)
    {
        $this->foreachFolder(ROUTES_DIR, ["php"], function ($file) use ($input, $output) {
            $search = [
                $this->current . '\\',
            ];

            $replace = [
                $input->getArgument('name') . '\\',
            ];

            $this->replaceIn($file, $search, $replace);
        });
    }
    
    private function updateComposer(InputInterface $input, OutputInterface $output)
    {
        $this->replaceIn(
            APP_DIR . "/../composer.json",
            $this->current . '\\',
            $input->getArgument('name') . '\\'
        );

        $output->writeln("Namespacing changed!");
        exec("composer dump-autoload -o");
    }

    private function foreachFolder($folder, $ext, callable $run)
    {
        FileSystem::foreachFileInFolder($folder, $ext, $run);
    }

    private function replaceIn($path, $search, $replace)
    {
        FileSystem::replaceIn($path, $search, $replace);
    }
}
