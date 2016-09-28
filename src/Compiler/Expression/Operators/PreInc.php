<?php

namespace PHPSA\Compiler\Expression\Operators;

use PhpParser\Node\Name;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class PreInc extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\PreInc';

    /**
     * ++{expr}
     *
     * @param \PhpParser\Node\Expr\PreInc $expr
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
                        $variable->inc();
                        return CompiledExpression::fromZvalValue($variable->getValue());
                }

                $context->notice(
                    'preinc.variable.wrong-type',
                    'You are trying to use pre increment operator on variable $' . $variableName .
                    ' with type: ' . $variable->getTypeName(),
                    $expr
                );
            } else {
                $context->notice(
                    'preinc.undefined-variable',
                    'You are trying to use pre increment operator on undefined variable: ' . $variableName,
                    $expr
                );
            }
        }

        return new CompiledExpression();
    }
}
