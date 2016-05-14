<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassMethod;
use PHPSA\Compiler\Expression;

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

        if (count($stmt->stmts) > 0) {
            foreach ($stmt->stmts as $statement) {
                \PHPSA\nodeVisitorFactory($statement, $context);
            }
        } else {
            $context->notice('not-implemented-body', 'Missing body', $stmt);
        }
    }
}
