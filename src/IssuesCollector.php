<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Compiler\GlobalVariable;
use PHPSA\Definition\AbstractDefinition;
use PHPSA\Definition\ParentDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class IssuesCollector
{
    /**
     * @var array
     */
    protected $issues = [];

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
