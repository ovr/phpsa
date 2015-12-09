<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;

class ForeachSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Foreach_';

    /**
     * @param \PhpParser\Node\Stmt\Foreach_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $expression = new Expression($context);
        $expression->compile($stmt->expr);

        if ($stmt->keyVar) {
            $keyExpression = new Expression($context);
            $keyExpression->declareVariable($stmt->keyVar);
        }

        if ($stmt->valueVar) {
            $valueExpression = new Expression($context);
            $valueExpression->declareVariable($stmt->valueVar);
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
