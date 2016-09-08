<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class IssuesCollector
{
    /**
     * @var array
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
        $this->issues[] = [
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line
        ];
    }

    /**
     * @return array
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
