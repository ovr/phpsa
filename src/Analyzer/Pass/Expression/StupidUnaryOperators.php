<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class StupidUnaryOperators implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for use of UnaryPlus `+$a` and suggests to use an int or float cast instead.';

    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        if (get_class($expr->expr) != get_class($expr)) {
            $context->notice(
                'stupid_unary_operators',
                'Better to use type casting then unary plus.',
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
        ];
    }
}
