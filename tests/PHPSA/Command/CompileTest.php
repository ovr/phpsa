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
    // simple as it is
    public function testIgnorePaths()
    {
        $ignore = ["/vendor"];
        $files = [
            "/vendor/path/to/1.php",
            "/vendor/path/to/2.php",
            "/vendor/path/to/3.php",
            "/app/path/to/model.php",
        ];

        $skip = 0;
        foreach ($files as $file) {
            foreach ($ignore as $item) {
                if (preg_match("#$item#", $file)) {
                    $skip++;
                    break;
                }
            }
        }

        $this->assertEquals($skip, 3);
    }
}
