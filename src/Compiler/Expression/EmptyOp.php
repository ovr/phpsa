<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable as VariableNode;
use PhpParser\Node\Name;

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
            $varName = $expr->expr->name;

            if ($varName instanceof Name) {
                $varName = $varName->parts[0];
            }

            $variable = $context->getSymbol($varName);

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
