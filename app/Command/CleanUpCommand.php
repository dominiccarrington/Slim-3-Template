<?php
namespace App\Command;

use App\Timer\Timer;
use FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CleanUpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName("optimize")
            ->setDescription("Clean up and optimize files");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cleanUpUseStatements($input, $output);
    }

    protected function cleanUpUseStatements(InputInterface $input, OutputInterface $output)
    {
        Timer::start('cleanup');
        $files = Finder::create()->in(APP_DIR)->name('*.php')
        ->append(Finder::create()->in(ROOT_DIR . '/bootstrap')->name('*.php'))
        ->append(Finder::create()->in(CONFIG_DIR)->name('*.php'))
        ->append(Finder::create()->in(RESOURCES_DIR)->name('*.php'));
        foreach ($files as $file) {
            $this->runUseStatementCleanup($file->getRealPath());
        }

        $output->writeln("Cleaned up use statements (Took: " . Timer::finish('cleanup') . ")");
    }

    public function runUseStatementCleanup($fileName)
    {
        $file = file_get_contents($fileName);
        $lines = explode(PHP_EOL, $file);
        $orgiLines = $lines;
        $useStatements = [];

        $stop = false;
        $i = 0;
        foreach ($lines as $line) {
            if ($stop) {
                break;
            }

            $start = substr(trim($line), 0, 3);

            if ($start == "use") {
                $useStatements[] = $lines[$i];
            } elseif ($start == "cla") { // Assume Class Start
                $stop = true;
            }
            $i++;
        }

        sort($useStatements);

        $stop = false;
        $useStatementNumber = 0;
        $i = 0;
        foreach ($lines as $line) {
            if ($stop) {
                break;
            }

            $start = substr(trim($line), 0, 3);

            if ($start == "use") {
                $lines[$i] = $useStatements[$useStatementNumber];
                $useStatementNumber++;
            } elseif ($start == "cla") { // Assume Class Start
                $stop = true;
            }
            $i++;
        }

        if ($lines != $orgiLines) {
            file_put_contents($fileName, implode(PHP_EOL, $lines));
        }
    }
}
