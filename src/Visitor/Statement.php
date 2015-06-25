<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\Context;
use PhpParser\Node;

class Statement
{
    /**
     * @var Context
     */
    protected $context;

    protected function passReturn(Node\Stmt\Return_ $st)
    {
        $expression = new Expression($st->expr, $this->context);
        $compiledExpression = $expression->compile($st->expr);
    }

    /**
     * @param Node\Stmt\If_ $st
     */
    public function passIf(Node\Stmt\If_ $st)
    {
        $expression = new Expression($st->cond, $this->context);
        $compiledExpression = $expression->compile($st->cond);

        if (count($st->stmts) > 0) {
            foreach ($st->stmts as $st) {
                if ($st instanceof Node\Stmt) {
                    $expr = new Statement($st, $this->context);
                } else {
                    $expr = new Expression($st, $this->context);
                    $expr->compile($st);
                }
            }
        } else {

        }
    }

    public function __construct(Node\Stmt $stmt, Context $context)
    {
        $this->context = $context;

        switch (get_class($stmt)) {
            case 'PhpParser\Node\Stmt\Return_':
                $this->passReturn($stmt);
                break;
            case 'PhpParser\Node\Stmt\If_':
                $this->passIf($stmt);
                break;
            default:
                $this->context->debug('Unknown statement: ' . get_class($stmt));
                break;
        }
    }
}