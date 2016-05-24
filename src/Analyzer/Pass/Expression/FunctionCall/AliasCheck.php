<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class AliasCheck extends AbstractFunctionCallAnalyzer
{
    protected $map = array(
        'join' => 'implode',
        'sizeof' => 'count'
    );

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset($this->map[$functionName])) {
            $context->notice(
                'fcall.alias',
                sprintf('%s() is an alias of function. Use %s(...).', $functionName, $this->map[$functionName]),
                $funcCall
            );
        }
    }
}
