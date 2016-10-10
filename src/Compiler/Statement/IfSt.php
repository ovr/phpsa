<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
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

        foreach ($ifStatement->stmts as $stmt) {
            \PHPSA\nodeVisitorFactory($stmt, $context);
        }

        $context->setCurrentBranch(Variable::BRANCH_CONDITIONAL_EXTERNAL);

        foreach ($ifStatement->elseifs as $elseIfStatement) {
            \PHPSA\nodeVisitorFactory($elseIfStatement, $context);
        }

        $context->setCurrentBranch(Variable::BRANCH_CONDITIONAL_FALSE);

        if ($ifStatement->else) {
            \PHPSA\nodeVisitorFactory($ifStatement->else, $context);
        }

        $context->setCurrentBranch(Variable::BRANCH_ROOT);
    }
}
