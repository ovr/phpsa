<?php

namespace PHPSA\Compiler\Expression\Operators;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class PreDec extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\PreDec';

    /**
     * --{expr}
     *
     * @param \PhpParser\Node\Expr\PreDec $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        if ($expr->var instanceof \PHPParser\Node\Expr\Variable) {
            $variableName = (string)$expr->var->name;
            $variable = $context->getSymbol($variableName);
            if ($variable) {
                $variable->incUse();

                switch ($variable->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::NUMBER:
                        $variable->dec();
                        return CompiledExpression::fromZvalValue($variable->getValue());
                }

                $context->notice(
                    'language_error',
                    'You are trying to use pre decrement operator on variable $' . $variableName .
                    ' with type: ' . $variable->getTypeName(),
                    $expr
                );
            } else {
                $context->notice(
                    'language_error',
                    'You are trying to use pre decrement operator on undefined variable: ' . $variableName,
                    $expr
                );
            }
        }

        return new CompiledExpression();
    }
}
