<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class MultipleUnaryOperators implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for use of multiple unary operators that cancel each other out. For example `!!boolean` or `- -int`. (there is a space between the two minus)';

    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        if (get_class($expr->expr) == get_class($expr)) {
            $context->notice(
                'multiple_unary_operators',
                "You are using multiple unary operators. This has no effect",
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
            Expr\UnaryPlus::class,
            Expr\UnaryMinus::class,
            Expr\BitwiseNot::class,
            Expr\BooleanNot::class,
        ];
    }
}
