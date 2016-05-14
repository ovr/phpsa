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

    public function __construct(\PhpParser\NodeAbstract $expression, Context $context)
    {
        parent::__construct();

        $this->context = $context;
        $this->expression = $expression;
    }

    /**
     * @return \PhpParser\Node\Expr
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }
}
