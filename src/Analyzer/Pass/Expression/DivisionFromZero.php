<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class DivisionFromZero implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for division from 0. For example: `0/$x`, `false%$x`';

    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($expr instanceof Expr\AssignOp) {
            $left = $compiler->compile($expr->var);
        } elseif ($expr instanceof Expr\BinaryOp) {
            $left = $compiler->compile($expr->left);
        }

        if ($left->getValue() == 0) {
            $context->notice(
                'division_from_zero',
                "You are trying to divide from zero",
                $expr
            );

            return true;
        }
        
        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Expr\BinaryOp\Div::class,
            Expr\BinaryOp\Mod::class,
            Expr\AssignOp\Div::class,
            Expr\AssignOp\Mod::class,
        ];
    }
}
