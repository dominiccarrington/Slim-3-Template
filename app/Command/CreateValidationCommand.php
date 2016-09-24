<?php
namespace App\Command;

use App\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CreateValidationCommand extends Command
{
    private $currentNamespace;
    private $ruleStub;
    private $exceptionStub;

    protected function configure()
    {
        $this
            ->setName('create:validation')
            ->setDescription('Create validation rule and exception')
            ->addArgument('rule name', InputArgument::REQUIRED, "The name of the rule to be created");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rule = $input->getArgument('rule name');

        $this->currentNamespace = App::getCurrentAppNamespace();
        $this->checkIfStubsExist();
        $this->createRule($rule, $output);
        $this->createException($rule, $output);
    }

    private function checkIfStubsExist()
    {
        if (file_exists(STUB_DIR . '/validation_rule.stub')) {
            $this->ruleStub = file_get_contents(STUB_DIR . '/validation_rule.stub');
        } else {
            throw new \RuntimeException("Validation rule stub (validation_rule.stub) should be found in " .
                STUB_DIR .
                ", it is missing.");
        }

        if (file_exists(STUB_DIR . '/validation_exception.stub')) {
            $this->exceptionStub = file_get_contents(STUB_DIR . '/validation_exception.stub');
        } else {
            throw new \RuntimeException("Validation Exception stub (validation_exception.stub) should be found in " .
                STUB_DIR .
                ", it is missing.");
        }
    }

    private function createRule($rule, OutputInterface $output)
    {
        file_put_contents(
            APP_DIR . "/Validation/Rules/{$rule}.php",
            $this->convertString($rule, $this->ruleStub)
        );
    }

    private function createException($rule, OutputInterface $output)
    {
        file_put_contents(
            APP_DIR . "/Validation/Exceptions/{$rule}Exception.php",
            $this->convertString($rule, $this->exceptionStub)
        );
    }

    private function convertString($rule, $text)
    {
        $conversion = [
            "[rule]" => $rule,
            "[namespace]" => $this->currentNamespace,
        ];
        $string = str_replace(array_keys($conversion), array_values($conversion), $text);

        return $string;
    }
}
