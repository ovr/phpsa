<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Event;

use PHPSA\Context;

class ExpressionAfterCompile extends \Webiny\Component\EventManager\Event
{
    const EVENT_NAME = 'expression.after-compile';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var \PhpParser\Node\Expr
     */
    protected $expression;

    /**
     * @var mixed
     */
    protected $result;

    public function __construct(\PhpParser\NodeAbstract $expression, Context $context, $result)
    {
        parent::__construct();

        $this->context = $context;
        $this->expression = $expression;
        $this->result = $result;
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

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
