<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

/**
 * PHPSA Application
 */
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

    const VERSION = '0.6.1';

    /**
     * Starts the application.
     */
    public function __construct()
    {
        parent::__construct('PHP Smart Analyzer', $this->getStringVersion());

        $this->add(new Command\CheckCommand());
        $this->add(new Command\CompileCommand());
        $this->add(new Command\DumpReferenceCommand());
        $this->add(new Command\DumpDocumentationCommand());

        $this->issuesCollector = new IssuesCollector();
        $this->configuration = new Configuration();
    }

    /**
     * Returns the version as a string.
     *
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

    /**
     * Returns CV Version.
     *
     * @return string
     */
    protected function getCVVersion()
    {
        exec('git describe --always', $version_mini_hash);

        return $version_mini_hash ? $version_mini_hash[0] : '';
    }

    /**
     * Get the configuration object.
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Get the IssuesCollector object.
     *
     * @return IssuesCollector
     */
    public function getIssuesCollector()
    {
        return $this->issuesCollector;
    }
}
