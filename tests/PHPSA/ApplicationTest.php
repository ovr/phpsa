<?php

namespace Tests\PHPSA;

use PHPSA\Application;

class ApplicationTest extends TestCase
{
    /**
     * @covers \PHPSA\Application::__construct
     */
    public function testConstructor()
    {
        $application = new Application();

        $this->assertInstanceOf('\PHPSA\Application', $application);
        $this->assertInstanceOf('\Symfony\Component\Console\Application', $application);
    }

    /**
     * @covers \PHPSA\Application::getConfiguration
     */
    public function testGetConfiguration()
    {
        $application = new Application();

        $this->assertInstanceOf('\PHPSA\Configuration', $application->getConfiguration());
    }

    /**
     * @covers \PHPSA\Application::getIssuesCollector
     */
    public function testGetIssueCollector()
    {
        $application = new Application();

        $this->assertInstanceOf('\PHPSA\IssuesCollector', $application->getIssuesCollector());
    }
}
