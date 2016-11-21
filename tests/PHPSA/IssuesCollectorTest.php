<?php

namespace Tests\PHPSA;

use PHPSA\IssueLocation;
use PHPSA\IssuesCollector;
use PHPSA\Issue;

class IssuesCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \PHPSA\IssuesCollector::addIssue()
     * @covers \PHPSA\IssuesCollector::getIssues()
     */
    public function testAddingIssue()
    {
        $collector = new IssuesCollector();
        $issue = new Issue(__FUNCTION__, 'Test issue', new IssueLocation(__FILE__, 26));

        $this->assertNull($collector->addIssue($issue));
        $this->assertCount(1, $collector->getIssues());
        $this->assertSame([$issue], $collector->getIssues());
    }
}
