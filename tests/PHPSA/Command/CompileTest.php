<?php

namespace Tests\PHPSA\Command;

use Tests\PHPSA\TestCase;
use PHPSA\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CompileTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();

        $command = $application->find('compile');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName(), 'path' => 'tests/PHPSA/Command/']);

        self::assertContains('Memory usage:', $commandTester->getDisplay());
    }
}
