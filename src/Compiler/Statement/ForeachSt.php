<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\Context;
use PHPSA\CompiledExpression;

class ForeachSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Foreach_';

    /**
     * @param \PhpParser\Node\Stmt\Foreach_ $stmt
     * @param Context $context
     * @return null|boolean
     */
    public function compile($stmt, Context $context)
    {
        $context->getExpressionCompiler()->compile($stmt->expr);

        if ($stmt->keyVar) {
            $context->getExpressionCompiler()->declareVariable($stmt->keyVar, null, CompiledExpression::MIXED);
        }

        if ($stmt->valueVar) {
            $context->getExpressionCompiler()->declareVariable($stmt->valueVar, null, CompiledExpression::MIXED);
        }

        foreach ($stmt->stmts as $statement) {
            \PHPSA\nodeVisitorFactory($statement, $context);
        }
    }
}
