<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Event;

use PHPSA\Context;

class ExpressionBeforeCompile extends \Webiny\Component\EventManager\Event
{
    const EVENT_NAME = 'expression.before-compile';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var \PhpParser\Node\Expr
     */
    private $expression;

    public function __construct(\PhpParser\Node\Expr $expression, Context $context)
    {
        parent::__construct();

        $this->context = $context;
        $this->expression = $expression;
    }
}
