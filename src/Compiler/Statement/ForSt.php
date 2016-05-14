<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassMethod;
use PHPSA\Compiler\Expression;

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
        if (count($stmt->init) > 0) {
            foreach ($stmt->init as $cond) {
                $context->getExpressionCompiler()->compile($cond);
            }
        }

        if (count($stmt->cond) > 0) {
            foreach ($stmt->cond as $cond) {
                $context->getExpressionCompiler()->compile($cond);
            }
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
