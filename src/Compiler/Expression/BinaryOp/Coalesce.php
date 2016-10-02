<?php

namespace PHPSA\Compiler\Expression\BinaryOp;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;

class Coalesce extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Coalesce';

    /**
     * {expr} ?? {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Coalesce $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        if ($expr->left instanceof Variable) {
            $varName = $expr->left->name;

            if ($varName instanceof Name) {
                $varName = $varName->parts[0];
            }

            $variable = $context->getSymbol($varName);

            if ($variable) {
                $variable->incUse();

                if ($variable->getValue() !== null) {
                    $leftCompiled = $context->getExpressionCompiler()->compile($expr->left);
                    return CompiledExpression::fromZvalValue($leftCompiled->getValue());
                }
            }
        }
        $rightCompiled = $context->getExpressionCompiler()->compile($expr->right);
        return CompiledExpression::fromZvalValue($rightCompiled->getValue());
    }
}
