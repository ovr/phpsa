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
    public function passIf(Node\Stmt\If_ $ifStatement)
    {
        $expression = new Expression($ifStatement->cond, $this->context);
        $compiledExpression = $expression->compile($ifStatement->cond);

        if (count($ifStatement->stmts) > 0) {
            foreach ($ifStatement->stmts as $st) {
                if ($st instanceof Node\Stmt) {
                    $expr = new Statement($st, $this->context);
                } else {
                    $expr = new Expression($st, $this->context);
                    $expr->compile($st);
                }
            }
        } else {
            //@todo implement
        }

        if (count($ifStatement->elseifs) > 0) {
            foreach ($ifStatement->elseifs as $elseIfStatement) {
                $expression = new Expression($elseIfStatement->cond, $this->context);
                $compiledExpression = $expression->compile($elseIfStatement->cond);

                if (count($elseIfStatement->stmts) > 0) {
                    foreach ($elseIfStatement->stmts as $st) {
                        if ($st instanceof Node\Stmt) {
                            $expr = new Statement($st, $this->context);
                        } else {
                            $expr = new Expression($st, $this->context);
                            $expr->compile($st);
                        }
                    }
                } else {
                    //@todo implement
                }
            }
        } else {
            //@todo implement
        }

        if ($ifStatement->else) {
            if (count($ifStatement->else->stmts) > 0) {
                foreach ($ifStatement->else->stmts as $st) {
                    if ($st instanceof Node\Stmt) {
                        $expr = new Statement($st, $this->context);
                    } else {
                        $expr = new Expression($st, $this->context);
                        $expr->compile($st);
                    }
                }
            } else {
                //@todo implement
            }
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
