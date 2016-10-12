<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class RandomApiMigration extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of old rand, srand, getrandmax functions and suggests alternatives.';

    protected $map = [
        'rand' => 'mt_rand',
        'srand' => 'mt_srand',
        'getrandmax' => 'mt_getrandmax'
    ];

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset($this->map[$functionName])) {
            $context->notice(
                'rand.api.migration',
                sprintf(
                    'Function %s() is not recommended, please use random_int/random_bytes (PHP 7) or mt_%s (not cryptographically secure) instead.',
                    $functionName,
                    $functionName
                ),
                $funcCall
            );
        }
    }
}
