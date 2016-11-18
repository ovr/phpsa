<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class NestedTernary implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of nested ternaries.';

    /**
     * @param Expr\Ternary $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Ternary $expr, Context $context)
    {
        if ($expr->if instanceof Expr\Ternary || $expr->else instanceof Expr\Ternary) {
            $context->notice(
                'nested_ternary',
                'Nested ternaries are confusing you should use if instead.',
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
            Expr\Ternary::class,
        ];
    }
}
