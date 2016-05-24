<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Command\CheckCommand;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var Configuration
     */
    public $configuration;

    /**
     * @var IssuesCollector
     */
    protected $issuesCollector;

    /**
     * @var Compiler
     */
    public $compiler;

    const VERSION = '0.5.0';

    public function __construct()
    {
        parent::__construct('PHP Smart Analyzer', $this->getStringVersion());

        $this->add(new CheckCommand());

        $this->issuesCollector = new IssuesCollector();
        $this->configuration = new Configuration();
    }

    /**
     * @return string
     */
    protected function getStringVersion()
    {
        $hash = $this->getCVVersion();
        if (!empty($hash)) {
            return self::VERSION . ' #' . $hash;
        }

        return self::VERSION;
    }

    protected function getCVVersion()
    {
        exec('git describe --always', $version_mini_hash);

        return $version_mini_hash ? $version_mini_hash[0] : '';
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return IssuesCollector
     */
    public function getIssuesCollector()
    {
        return $this->issuesCollector;
    }
}
