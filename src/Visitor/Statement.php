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
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($st->expr);
    }

    /**
     * @param Node\Stmt\If_ $st
     */
    public function passIf(Node\Stmt\If_ $ifStatement)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($ifStatement->cond);

        if (count($ifStatement->stmts) > 0) {
            foreach ($ifStatement->stmts as $st) {
                $result = \PHPSA\nodeVisitorFactory($st, $this->context);
            }
        } else {
            //@todo implement
        }

        if (count($ifStatement->elseifs) > 0) {
            foreach ($ifStatement->elseifs as $elseIfStatement) {
                $expression = new Expression($this->context);
                $compiledExpression = $expression->compile($elseIfStatement->cond);

                if (count($elseIfStatement->stmts) > 0) {
                    foreach ($elseIfStatement->stmts as $st) {
                        $result = \PHPSA\nodeVisitorFactory($st, $this->context);
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
                    $result = \PHPSA\nodeVisitorFactory($st, $this->context);
                }
            } else {
                //@todo implement
            }
        }
    }

    /**
     * @param Node\Stmt\Switch_ $switchStatement
     */
    public function passSwitch(Node\Stmt\Switch_ $switchStatement)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($switchStatement->cond);

        if ($switchStatement->cases) {
            foreach ($switchStatement->cases as $case) {
                if ($case->cond) {
                    $expression = new Expression($this->context);
                    $compiledExpression = $expression->compile($case->cond);
                }

                if (count($case->stmts) > 0) {
                    foreach ($case->stmts as $st) {
                        $result = \PHPSA\nodeVisitorFactory($st, $this->context);
                    }
                } else {
                    //@todo implement
                }
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
            case 'PhpParser\Node\Stmt\Switch_':
                $this->passSwitch($stmt);
                break;
            default:
                $this->context->debug('Unknown statement: ' . get_class($stmt));
                break;
        }
    }
}
