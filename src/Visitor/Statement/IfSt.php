<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;

class IfSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\If_';

    /**
     * @param \PhpParser\Node\Stmt\If_ $ifStatement
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($ifStatement, Context $context)
    {
        $expression = new Expression($context);
        $expression->compile($ifStatement->cond);

        if (count($ifStatement->stmts) > 0) {
            foreach ($ifStatement->stmts as $stmt) {
                \PHPSA\nodeVisitorFactory($stmt, $context);
            }
        } else {
            $context->notice('not-implemented-body', 'Missing body', $ifStatement);
        }

        if (count($ifStatement->elseifs) > 0) {
            foreach ($ifStatement->elseifs as $elseIfStatement) {
                $expression = new Expression($context);
                $expression->compile($elseIfStatement->cond);

                if (count($elseIfStatement->stmts) > 0) {
                    foreach ($elseIfStatement->stmts as $stmt) {
                        \PHPSA\nodeVisitorFactory($stmt, $context);
                    }
                } else {
                    $context->notice('not-implemented-body', 'Missing body', $elseIfStatement);
                }
            }
        } else {
            //@todo implement
        }

        if ($ifStatement->else) {
            if (count($ifStatement->else->stmts) > 0) {
                foreach ($ifStatement->else->stmts as $stmt) {
                    \PHPSA\nodeVisitorFactory($stmt, $context);
                }
            } else {
                $context->notice('not-implemented-body', 'Missing body', $ifStatement->else);
            }
        }
    }
}
