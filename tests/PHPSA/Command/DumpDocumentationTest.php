<?php

namespace Tests\PHPSA\Command;

use Tests\PHPSA\TestCase;
use PHPSA\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DumpConfigurationTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();

        $command = $application->find('config:dump-documentation');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName()]);

        self::assertContains('Next:', $commandTester->getDisplay());
    }
}
