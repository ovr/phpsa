<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

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
            if ($expr->name->parts[0] === 'true') {
                return new CompiledExpression(CompiledExpression::BOOLEAN, true);
            }

            if ($expr->name->parts[0] === 'false') {
                return new CompiledExpression(CompiledExpression::BOOLEAN, false);
            }
        }

        /**
         * @todo Implement check
         */
        return $compiler->compile($expr->name);
    }
}
