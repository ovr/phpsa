<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class ErrorSuppression implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of the `@` operator to silence errors.';

    /**
     * @param Expr\ErrorSuppress $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\ErrorSuppress $expr, Context $context)
    {
        $context->notice(
            'error.suppression',
            'Please don\'t suppress errors with the @ operator.',
            $expr
        );

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Expr\ErrorSuppress::class
        ];
    }
}
