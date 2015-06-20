<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class Application extends \Symfony\Component\Console\Application
{
    protected $finder;

    public function __construct()
    {
        parent::__construct('PHP Static Analyzer', '0.0.1-dev');

        $this->add(new \PHPSA\Command\CheckCommand());
    }
}
