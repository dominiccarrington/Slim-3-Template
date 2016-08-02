<?php
namespace App\Command;

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
        // $output->writeln("Change name of app to " . $input->getArgument("name"));
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
        $this->foreachFolder(APP_DIR, function ($file) {
            $search = [
                'namespace ' . $this->current . ';',
                $this->current . '\\',
            ];

            $replace = [
                'namespace ' . $input->getArgument('name') . ';',
                $input->getArgument('name') . '\\',
            ]; 

            $this->replaceIn($path, $search, $replace);
        });
    }

    private function changeBootstrap(InputInterface $input, OutputInterface $output)
    {

    }
    
    private function changeConfig(InputInterface $input, OutputInterface $output)
    {

    }
    
    private function changeRoutes(InputInterface $input, OutputInterface $output)
    {

    }
    
    private function updateComposer(InputInterface $input, OutputInterface $output)
    {
        
    }

    private function foreachFolder($folder, $ext, callable $run)
    {
        $ff = scandir($folder);
        $ff = array_slice($ff, 2); 

        foreach ($ff as $f) {
            if (is_dir($folder . "/" . $f)) {
                $this->foreachFolder($folder . "/" . $f, $run);
            } else {
                $fileExtension = explode(".", $f);
                $fileExtension = isset($fileExtension[1]) ? $fileExtension[1] : "";
                if (is_array($ext) && in_array($fileExtension, $ext)) {
                    $run($folder . "/" . $f);
                }
            }
        }
    }

    private function replaceIn($path, $search, $replace)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
