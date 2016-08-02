<?php
namespace App\Command;

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
        // ...
    }
}
