<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class ArrayShortDefinition
{
    /**
     * @param Expr\Array_ $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Array_ $expr, Context $context)
    {
        if ($expr->getAttribute('kind') == Expr\Array_::KIND_LONG) {
            $context->notice(
                'array.short-syntax',
                'Please use [] (short syntax) for array definition.',
                $expr
            );

            return true;
        }

        return false;
    }
}
