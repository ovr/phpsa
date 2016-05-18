<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class DebugCode implements PassFunctionCallInterface
{
    use ResolveExpressionTrait;

    protected $map = array(
        'var_dump' => 'var_dump',
        'var_export' => 'var_export',
        'debug_zval_dump' => 'debug_zval_dump'
    );

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset($this->map[$functionName])) {
            if ($funcCall->getDocComment()) {
                $phpdoc = new \phpDocumentor\Reflection\DocBlock($funcCall->getDocComment()->getText());
                if ($phpdoc->hasTag('expected')) {
                    return true;
                }
            }

            $context->notice(
                'debug.code',
                sprintf('Function %s() is a debug code, please don`t use it in production.', $functionName),
                $funcCall
            );
        }
    }
}
