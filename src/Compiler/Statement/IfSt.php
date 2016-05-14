<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Variable;

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
        $context->setCurrentBranch(Variable::BRANCH_CONDITIONAL_TRUE);

        $context->getExpressionCompiler()->compile($ifStatement->cond);

        if (count($ifStatement->stmts) > 0) {
            foreach ($ifStatement->stmts as $stmt) {
                \PHPSA\nodeVisitorFactory($stmt, $context);
            }
        } else {
            $context->notice('not-implemented-body', 'Missing body', $ifStatement);
        }

        $context->setCurrentBranch(Variable::BRANCH_CONDITIONAL_EXTERNAL);

        if (count($ifStatement->elseifs) > 0) {
            foreach ($ifStatement->elseifs as $elseIfStatement) {
                $context->getExpressionCompiler()->compile($elseIfStatement->cond);

                if (count($elseIfStatement->stmts) > 0) {
                    foreach ($elseIfStatement->stmts as $stmt) {
                        \PHPSA\nodeVisitorFactory($stmt, $context);
                    }
                } else {
                    $context->notice('not-implemented-body', 'Missing body', $elseIfStatement);
                }
            }
        }

        $context->setCurrentBranch(Variable::BRANCH_CONDITIONAL_FALSE);

        if ($ifStatement->else) {
            if (count($ifStatement->else->stmts) > 0) {
                foreach ($ifStatement->else->stmts as $stmt) {
                    \PHPSA\nodeVisitorFactory($stmt, $context);
                }
            } else {
                $context->notice('not-implemented-body', 'Missing body', $ifStatement->else);
            }
        }

        $context->setCurrentBranch(Variable::BRANCH_ROOT);
    }
}
