<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class Application extends \Symfony\Component\Console\Application
{
    protected $finder;

    const VERSION = '0.0.1-dev';

    public function __construct()
    {
        parent::__construct('PHP Static Analyzer', self::VERSION . ' #' . $this->getCVVersion());

        $this->add(new \PHPSA\Command\CheckCommand());
    }

    protected function getCVVersion()
    {
        exec('git describe --always', $version_mini_hash);

        return $version_mini_hash ? $version_mini_hash[0] : '';
    }
}
