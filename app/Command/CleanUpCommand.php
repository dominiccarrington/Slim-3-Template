<?php
namespace App\Command;

use FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $output->writeln("Optimizing app dir");
        FileSystem::foreachFileInFolder(APP_DIR, ["php"], [$this, "runUseStatementCleanup"]);
        $output->writeln("Optimizing config dir");
        FileSystem::foreachFileInFolder(CONFIG_DIR, ["php"], [$this, "runUseStatementCleanup"]);
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
