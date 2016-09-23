<?php

namespace PHPSA\Compiler\Expression\Operators;

use PhpParser\Node\Name;
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
            $variableName = $expr->var->name;
            if ($variableName instanceof Name) {
                $variableName = $variableName->parts[0];
            }

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
                    'predec.variable.wrong-type',
                    'You are trying to use pre decrement operator on variable $' . $variableName .
                    ' with type: ' . $variable->getTypeName(),
                    $expr
                );
            } else {
                $context->notice(
                    'predec.undefined-variable',
                    'You are trying to use pre decrement operator on undefined variable: ' . $variableName,
                    $expr
                );
            }

            return new CompiledExpression();
        }

        $compiledExpression = $context->getExpressionCompiler()->compile($expr->var);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::NUMBER:
                $value = $compiledExpression->getValue();
                return CompiledExpression::fromZvalValue(--$value);
        }

        return new CompiledExpression();
    }
}
