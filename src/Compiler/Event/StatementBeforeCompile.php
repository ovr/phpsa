<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Event;

use PHPSA\Context;

class StatementBeforeCompile extends \Webiny\Component\EventManager\Event
{
    const EVENT_NAME = 'statement.before-compile';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var \PhpParser\Node\Expr
     */
    private $statement;

    public function __construct(\PhpParser\Node\Stmt $statement, Context $context)
    {
        parent::__construct();

        $this->context = $context;
        $this->statement = $statement;
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
}
