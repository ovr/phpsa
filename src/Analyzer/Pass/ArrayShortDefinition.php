<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass;

use PhpParser\Node\Expr;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class ArrayShortDefinition
{
    public function pass(Expr\Array_ $expr, Context $context)
    {
        if ($expr->getAttribute('kind') == Expr\Array_::KIND_LONG) {
            $context->notice(
                'array.short-syntax',
                'Please use [] (short syntax) for array definition.',
                $expr
            );
        }
    }
}
