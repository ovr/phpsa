<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class DivisionByOne implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for division by 1. For example: `$x/1`, `$x%true`';

    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($expr instanceof Expr\AssignOp) {
            $right = $compiler->compile($expr->expr);
        } elseif ($expr instanceof Expr\BinaryOp) {
            $right = $compiler->compile($expr->right);
        }

        if ($right->getValue() == 1) {
            $context->notice(
                'division_by_one',
                "You are trying to divide by one",
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
