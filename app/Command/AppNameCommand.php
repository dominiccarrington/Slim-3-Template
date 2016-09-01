<?php
namespace App\Command;

use App\App;
use App\Timer\Timer;
use FileSystem;
use Illuminate\Support\Composer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class AppNameCommand extends Command
{
    private $current;
    private $composer;
    private $files;

    protected function configure()
    {
        $this->files = new FileSystem();
        $this->composer = new Composer($this->files, ROOT_DIR);
        $this
            ->setName("app:name")
            ->setDescription("Change the namespace of the application")
            ->addArgument("name", InputArgument::REQUIRED, "The new name of the application")
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Timer::start('namespace');
        $this->current = App::getCurrentAppNamespace();
        $this->changeAppNamespace($input, $output);
        $this->changeBootstrap($input, $output);
        $this->changeConfig($input, $output);
        $this->updateMigrations($input, $output);        
        $this->updateComposer($input, $output);

        
        $output->writeln("Namespacing changed! (Time Taken: " . Timer::finish('namespace') . "s)");
    }

    private function changeAppNamespace(InputInterface $input, OutputInterface $output)
    {
        $this->updateNamespace(APP_DIR, $input);
    }

    private function changeBootstrap(InputInterface $input, OutputInterface $output)
    {
        $this->updateNamespace(ROOT_DIR . "/bootstrap", $input);
    }
    
    private function changeConfig(InputInterface $input, OutputInterface $output)
    {
        $this->updateNamespace(CONFIG_DIR, $input);
    }
    
    private function updateMigrations(InputInterface $input, OutputInterface $output)
    {
        $this->replaceIn(
            ROOT_DIR . "/phinx.php",
            $this->current . '\\',
            $input->getArgument('name') . '\\'
        );

        $this->updateNamespace(RESOURCES_DIR, $input);
    }

    private function updateComposer(InputInterface $input, OutputInterface $output)
    {
        $this->replaceIn(
            APP_DIR . "/../composer.json",
            $this->current . '\\',
            $input->getArgument('name') . '\\'
        );
        $this->composer->dumpOptimized();
    }

    protected function updateNamespace($path, $input)
    {
        $files = [];
        if ($this->files->isDirectory($path)) {
            $files = Finder::create()
                ->in($path)
                ->contains($this->current)
                ->name("*.php");
        } else {
            $files = [$path];
        }

        foreach ($files as $file) {
            $search = [
                'namespace ' . $this->current . ';',
                $this->current . '\\',
            ];

            $replace = [
                'namespace ' . $input->getArgument('name') . ';',
                $input->getArgument('name') . '\\',
            ];

            if ($file instanceof Symfony\Component\Finder\SplFileInfo) {
                $this->replaceIn($file->getRealPath(), $search, $replace);
            } else {
                $this->replaceIn($file, $search, $replace);
            }
        }
    }

    protected function replaceIn($path, $search, $replace)
    {
        $this->files->put($path, str_replace($search, $replace, $this->files->get($path)));
    }
}
