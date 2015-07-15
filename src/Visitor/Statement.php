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

    /**
     * @param Node\Stmt\Return_ $returnStmt
     */
    protected function passReturn(Node\Stmt\Return_ $returnStmt)
    {
        $expression = new Expression($this->context);
        $expression->compile($returnStmt->expr);
    }

    /**
     * @param Node\Stmt\Return_ $returnStmt
     */
    protected function passWhile(Node\Stmt\While_ $baseStmt)
    {
        $expression = new Expression($this->context);
        $expression->compile($baseStmt->cond);

        if (count($baseStmt->stmts) > 0) {
            foreach ($baseStmt->stmts as $statement) {
                \PHPSA\nodeVisitorFactory($statement, $this->context);
            }
        } else {
            return $this->context->notice(
                'not-implemented-body', 'Missing body', $baseStmt
            );
        }
    }

    /**
     * @param Node\Stmt\For_ $returnStmt
     */
    protected function passFor(Node\Stmt\For_ $baseStmt)
    {
        if (count($baseStmt->init) > 0) {
            foreach ($baseStmt->init as $cond) {
                $expression = new Expression($this->context);
                $expression->compile($cond);
            }
        }

        if (count($baseStmt->cond) > 0) {
            foreach ($baseStmt->cond as $cond) {
                $expression = new Expression($this->context);
                $expression->compile($cond);
            }
        }

        if (count($baseStmt->stmts) > 0) {
            foreach ($baseStmt->stmts as $statement) {
                \PHPSA\nodeVisitorFactory($statement, $this->context);
            }
        } else {
            return $this->context->notice(
                'not-implemented-body', 'Missing body', $baseStmt
            );
        }
    }

    /**
     * @param Node\Stmt\If_ $ifStatement
     */
    public function passIf(Node\Stmt\If_ $ifStatement)
    {
        $expression = new Expression($this->context);
        $expression->compile($ifStatement->cond);

        if (count($ifStatement->stmts) > 0) {
            foreach ($ifStatement->stmts as $stmt) {
                \PHPSA\nodeVisitorFactory($stmt, $this->context);
            }
        } else {
            $this->context->notice(
                'not-implemented-body', 'Missing body', $ifStatement
            );
        }

        if (count($ifStatement->elseifs) > 0) {
            foreach ($ifStatement->elseifs as $elseIfStatement) {
                $expression = new Expression($this->context);
                $expression->compile($elseIfStatement->cond);

                if (count($elseIfStatement->stmts) > 0) {
                    foreach ($elseIfStatement->stmts as $stmt) {
                        \PHPSA\nodeVisitorFactory($stmt, $this->context);
                    }
                } else {
                    $this->context->notice(
                        'not-implemented-body', 'Missing body', $elseIfStatement
                    );
                }
            }
        } else {
            //@todo implement
        }

        if ($ifStatement->else) {
            if (count($ifStatement->else->stmts) > 0) {
                foreach ($ifStatement->else->stmts as $stmt) {
                    \PHPSA\nodeVisitorFactory($stmt, $this->context);
                }
            } else {
                $this->context->notice(
                    'not-implemented-body', 'Missing body', $ifStatement->else
                );
            }
        }
    }

    /**
     * @param Node\Stmt\Switch_ $switchStatement
     */
    public function passSwitch(Node\Stmt\Switch_ $switchStatement)
    {
        $expression = new Expression($this->context);
        $expression->compile($switchStatement->cond);

        if (count($switchStatement->cases)) {
            foreach ($switchStatement->cases as $case) {
                if ($case->cond) {
                    $expression = new Expression($this->context);
                    $expression->compile($case->cond);
                }

                if (count($case->stmts) > 0) {
                    foreach ($case->stmts as $stmt) {
                        \PHPSA\nodeVisitorFactory($stmt, $this->context);
                    }
                } else {
                    $this->context->notice(
                        'not-implemented-body', 'Missing body', $case
                    );
                }
            }
        } else {
            //@todo implement
        }
    }

    /**
     * @param Node\Stmt $stmt
     * @param Context $context
     */
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
            case 'PhpParser\Node\Stmt\While_':
                $this->passWhile($stmt);
                break;
            case 'PhpParser\Node\Stmt\For_':
                $this->passFor($stmt);
                break;
            case 'PhpParser\Node\Stmt\Break_':
                //@todo implement
                break;
            default:
                $this->context->debug('Unknown statement: ' . get_class($stmt));
                break;
        }
    }
}
