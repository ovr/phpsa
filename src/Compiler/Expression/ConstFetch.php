<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node;

class ConstFetch extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\ConstFetch';

    /**
     * true, CONSTANTNAME, ...
     *
     * @param \PhpParser\Node\Expr\ConstFetch $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($expr->name instanceof Node\Name) {
            if ($expr->name->toString() === 'true') {
                return new CompiledExpression(CompiledExpression::BOOLEAN, true);
            }

            if ($expr->name->toString() === 'false') {
                return new CompiledExpression(CompiledExpression::BOOLEAN, false);
            }
        }

        /**
         * @todo Implement check
         */
        return $compiler->compile($expr->name);
    }
}
