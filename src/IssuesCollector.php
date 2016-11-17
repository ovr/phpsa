<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

/**
 * A collection of Issues
 */
class IssuesCollector
{
    /**
     * @var Issue[]
     */
    protected $issues = [];

    /**
     * @param Issue $issue
     */
    public function addIssue(Issue $issue)
    {
        $this->issues[] = $issue;
    }

    /**
     * @return Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
