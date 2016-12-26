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

    const VERSION = '0.6.2';

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
     * @codeCoverageIgnore
     */
    protected function getStringVersion()
    {
        $hash = $this->getCVVersion();
        if ($hash) {
            return self::VERSION . ' #' . $hash;
        }

        return self::VERSION;
    }

    /**
     * Returns CV Version.
     *
     * @return string|null
     * @codeCoverageIgnore
     */
    protected function getCVVersion()
    {
        if (!extension_loaded('pcntl')) {
            return null;
        }

        $proc = proc_open(
            'git describe --always',
            [
                // STDOUT
                1 => ['pipe','w'],
                // STDERR
                2 => ['pipe','w']
            ],
            $pipes
        );
        if ($proc) {
            $stdout = stream_get_contents($pipes[1]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $exitCode = proc_close($proc);
            if ($exitCode === 0) {
                return $stdout;
            }
        }

        return null;
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
