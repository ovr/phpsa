<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class WhileSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\While_';

    /**
     * @param \PhpParser\Node\Stmt\While_ $stmt
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
