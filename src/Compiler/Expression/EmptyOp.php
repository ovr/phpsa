<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable as VariableNode;

class EmptyOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Empty_';

    /**
     * empty({expr]})
     *
     * @param \PhpParser\Node\Expr\Empty_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        if ($expr->expr instanceof VariableNode) {
            $variable = $context->getSymbol((string)$expr->expr->name);

            if ($variable) {
                $variable->incUse();

                if ($variable->getValue() !== null && $variable->getValue() != false) {
                    return CompiledExpression::fromZvalValue(true);
                }
            }
        }

        return CompiledExpression::fromZvalValue(false);
    }
}
