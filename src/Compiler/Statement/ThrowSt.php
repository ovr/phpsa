<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ThrowSt extends AbstractCompiler
{
    protected $name = \PhpParser\Node\Stmt\Throw_::class;

    /**
     * @param \PhpParser\Node\Stmt\Throw_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $context->getExpressionCompiler()->compile($stmt->expr);
    }
}
