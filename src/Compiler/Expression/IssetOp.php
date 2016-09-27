<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;

class IssetOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Isset_';

    /**
     * isset({expr]})
     *
     * @param \PhpParser\Node\Expr\Isset_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        foreach ($expr->vars as $var) {
            if ($var instanceof Variable) {
                $varName = $var->name;

                if ($varName instanceof Name) {
                    $varName = $varName->parts[0];
                }

                $variable = $context->getSymbol($varName);

                if ($variable) {
                    $variable->incUse();

                    if ($variable->getValue() !== null) {
                        return CompiledExpression::fromZvalValue(true);
                    }
                }
                return CompiledExpression::fromZvalValue(false);
            }
        }
    }
}
