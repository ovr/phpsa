<?php

namespace PHPSA\Compiler\Event;

use PHPSA\Context;

class ScalarBeforeCompile extends \Webiny\Component\EventManager\Event
{
    const EVENT_NAME = 'scalar.before-compile';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var \PhpParser\Node\Scalar
     */
    private $scalar;

    public function __construct(\PhpParser\NodeAbstract $scalar, Context $context)
    {
        parent::__construct();

        $this->context = $context;
        $this->scalar = $scalar;
    }

    /**
     * @return \PhpParser\Node\Scalar
     */
    public function getScalar()
    {
        return $this->scalar;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }
}
