<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class Variable extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Variable';

    /**
     * $a or $$a
     *
     * @param \PhpParser\Node\Expr\Variable $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $nameCE = $context->getExpressionCompiler()->compile($expr->name);
        if ($nameCE->isString() && $nameCE->isCorrectValue()) {
            $variable = $context->getSymbol($nameCE->getValue());
            if ($variable) {
                $variable->incGets();
                return new CompiledExpression($variable->getType(), $variable->getValue(), $variable);
            }

            $context->notice(
                'undefined-variable',
                sprintf('You are trying to use an undefined variable $%s', $nameCE->getValue()),
                $expr
            );
        }

        return new CompiledExpression();
    }
}
