<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class DoSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Do_';

    /**
     * @param \PhpParser\Node\Stmt\Do_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $context->getExpressionCompiler()->compile($stmt->cond);

        foreach ($stmt->stmts as $statement) {
            \PHPSA\nodeVisitorFactory($statement, $context);
        }
    }
}
