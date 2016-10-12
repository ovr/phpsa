<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class UseCast extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of functions like boolval, strval and others and suggests the use of casts.';

    protected $map = [
        'boolval' => 'bool',
        'intval' => 'int',
        'floatval' => 'double',
        'doubleval' => 'double',
        'strval' => 'string'
    ];

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset($this->map[$functionName])) {
            /**
             * Exclusion via intval with 2 args intval($number, int $base = 10);
             */
            if ($functionName == 'intval' && count($funcCall->args) > 1) {
                return;
            }

            $context->notice(
                'fcall.cast',
                sprintf('Please use (%s) cast instead of function call.', $this->map[$functionName]),
                $funcCall
            );
        }
    }
}
