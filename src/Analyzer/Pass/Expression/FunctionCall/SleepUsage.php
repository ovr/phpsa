<?php

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class SleepUsage extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of different sleep functions which can lead to a DoS vulnerability.';

    /**
     * @var array different sleep functions
     */
    protected $map = [
        'sleep' => 'sleep',
        'usleep' => 'usleep',
        'time_nanosleep' => 'time_nanosleep',
        'time_sleep_until' => 'time_sleep_until'
    ];

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if (!$functionName || !isset($this->map[$functionName])) {
            return false;
        }

        $context->notice(
            'sleep.usage',
            sprintf('Function %s() can cause a denial of service vulnerability.', $functionName),
            $funcCall
        );

        return true;
    }
}
