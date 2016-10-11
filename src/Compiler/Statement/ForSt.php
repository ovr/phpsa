<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ForSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\For_';

    /**
     * @param \PhpParser\Node\Stmt\For_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        foreach ($stmt->init as $init) {
            $context->getExpressionCompiler()->compile($init);
        }

        foreach ($stmt->cond as $cond) {
            $context->getExpressionCompiler()->compile($cond);
        }

        foreach ($stmt->loop as $loop) {
            $context->getExpressionCompiler()->compile($loop);
        }

        foreach ($stmt->stmts as $statement) {
            \PHPSA\nodeVisitorFactory($statement, $context);
        }
    }
}
