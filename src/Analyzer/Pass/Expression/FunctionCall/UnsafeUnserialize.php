<?php

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class UnsafeUnserialize extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of `unserialize()` without a 2nd parameter defining the allowed classes. Requires PHP 7.0+';

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);

        if ($functionName !== 'unserialize') {
            return false;
        }

        if (count($funcCall->args) < 2) {
            $context->notice(
                'unsafe.unserialize',
                sprintf('unserialize() should be used with a list of allowed classes or false as 2nd parameter.'),
                $funcCall
            );
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function getMetadata()
    {
        $metaData = parent::getMetadata();
        $metaData->setRequiredPhpVersion('7.0');

        return $metaData;
    }
}
