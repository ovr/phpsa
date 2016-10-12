<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class CompareWithArray implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for `{type array} > 1` and similar and suggests use of `count()`.';

    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();
        $left = $compiler->compile($expr->left);
        $right = $compiler->compile($expr->right);

        if ($left->isArray() || $right->isArray()) {
            $context->notice(
                'compare_with_array',
                "You are comparing an array. Did you want to use count()?",
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
            Expr\BinaryOp\Greater::class,
            Expr\BinaryOp\GreaterOrEqual::class,
            Expr\BinaryOp\Smaller::class,
            Expr\BinaryOp\SmallerOrEqual::class,
        ];
    }
}
