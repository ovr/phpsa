<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

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
            $context->getExpressionCompiler()->declareVariable($stmt->keyVar);
        }

        if ($stmt->valueVar) {
            $context->getExpressionCompiler()->declareVariable($stmt->valueVar);
        }

        if (count($stmt->stmts) > 0) {
            foreach ($stmt->stmts as $statement) {
                \PHPSA\nodeVisitorFactory($statement, $context);
            }
        } else {
            return $context->notice('not-implemented-body', 'Missing body', $stmt);
        }
    }
}
