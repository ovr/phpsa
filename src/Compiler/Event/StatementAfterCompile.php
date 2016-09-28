<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Event;

use PHPSA\Context;

class StatementAfterCompile extends \Webiny\Component\EventManager\Event
{
    const EVENT_NAME = 'statement.after-compile';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var \PhpParser\Node\Expr
     */
    protected $statement;

    /**
     * @var mixed
     */
    protected $result;

    public function __construct(\PhpParser\Node\Stmt $statement, Context $context, $result)
    {
        parent::__construct();

        $this->context = $context;
        $this->statement = $statement;
        $this->result = $result;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return \PhpParser\Node\Expr
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
