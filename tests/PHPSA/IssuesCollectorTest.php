<?php

namespace Tests\PHPSA;

use PHPSA\IssueLocation;
use PHPSA\IssuesCollector;
use PHPSA\Issue;

class IssuesCollectorTest extends TestCase
{
    /**
     * @covers \PHPSA\IssuesCollector::addIssue()
     * @covers \PHPSA\IssuesCollector::getIssues()
     */
    public function testAddingIssue()
    {
        $collector = new IssuesCollector();
        $issue = new Issue(__FUNCTION__, 'Test issue', new IssueLocation(__FILE__, 26));

        self::assertNull($collector->addIssue($issue));
        self::assertCount(1, $collector->getIssues());
        self::assertSame([$issue], $collector->getIssues());
    }
}
