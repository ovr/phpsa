<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class IssuesCollector
{
    /**
     * @var Issue[]
     */
    protected $issues = [];

    /**
     * @param int $type
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function addIssue($type, $message, $file, $line)
    {
        $this->issues[] = new Issue(
            $type,
            $message,
            [
                'path' => $file,
                'lines' => [
                    'begin' => $line,
                    'end' => $line,
                ]
            ]
        );
    }

    /**
     * @return Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
