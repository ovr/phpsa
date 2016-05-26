<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClosureDefinition;

class Closure extends AbstractExpressionCompiler
{
    protected $name = \PhpParser\Node\Expr\Closure::class;

    /**
     * @param \PhpParser\Node\Expr\Closure $expr
     * @param Context $context
     * @return mixed
     */
    protected function compile($expr, Context $context)
    {
        return new CompiledExpression(
            CompiledExpression::CALLABLE_TYPE,
            new ClosureDefinition($expr)
        );
    }
}
