<?php
namespace App\Command;

use App\App;
use FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Composer;

class AppNameCommand extends Command
{
    private $current;
    private $composer;

    protected function configure()
    {
        $this->composer = new Composer(new FileSystem, ROOT_DIR);
        $this
            ->setName("app:name")
            ->setDescription("Change the namespace of the application")
            ->addArgument("name", InputArgument::REQUIRED, "The new name of the application")
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->current = App::getCurrentAppNamespace();
        $this->changeAppNamespace($input, $output);
        $this->changeBootstrap($input, $output);
        $this->changeConfig($input, $output);
        $this->changeRoutes($input, $output);
        $this->updateMigrations($input, $output);        
        $this->updateComposer($input, $output);
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
        $this->foreachFolder(ROOT_DIR . "/bootstrap", ["php"], function ($file) use ($input, $output) {
            $search = [
                $this->current . '\\',
            ];

            $replace = [
                $input->getArgument('name') . '\\',
            ];

            $this->replaceIn($file, $search, $replace);
        });
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
        $this->replaceIn(
            CONFIG_DIR . "/routes.php",
            $this->current . '\\',
            $input->getArgument('name') . '\\'
        );
    }
    
    private function updateMigrations(InputInterface $input, OutputInterface $output)
    {
        $this->replaceIn(
            ROOT_DIR . "/phinx.php",
            $this->current . '\\',
            $input->getArgument('name') . '\\'
        );

        $this->foreachFolder(RESOURCES_DIR, ['php'], function ($file) use ($input, $output) {
            $this->replaceIn($file, $this->current . '\\', $input->getArgument('name') . '\\');
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
        $this->composer->dumpOptimized();
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
