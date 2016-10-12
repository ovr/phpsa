<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class AliasCheck extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of alias functions and suggests the use of the originals.';

    protected $map = [
        'join' => 'implode',
        'sizeof' => 'count',
        'pos' => 'current',
        'strchr' => 'strstr',
        'show_source' => 'highlight_file',
        'key_exists' => 'array_key_exists',
        'is_real' => 'is_float',
        'is_double' => 'is_float',
        'is_integer' => 'is_int',
        'is_long' => 'is_int',
        'ini_alter' => 'ini_set',
        'fputs' => 'fwrite',
        'chop' => 'rtrim'
    ];

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset($this->map[$functionName])) {
            $context->notice(
                'fcall.alias',
                sprintf('%s() is an alias of another function. Use %s() instead.', $functionName, $this->map[$functionName]),
                $funcCall
            );
        }
    }
}
