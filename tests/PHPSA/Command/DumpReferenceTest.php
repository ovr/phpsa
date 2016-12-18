<?php

namespace Tests\PHPSA\Command;

use Tests\PHPSA\TestCase;
use PHPSA\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DumpReferenceTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();

        $command = $application->find('config:dump-reference');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName()]);

        $this->assertContains('language_error', $commandTester->getDisplay());
    }
}
