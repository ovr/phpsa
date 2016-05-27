<?php

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;
use PHPSA\Definition\ClassMethod;

class ArgumentUnpacking extends AbstractFunctionCallAnalyzer
{

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName === "func_get_args") {
            $scopePointer = $context->scopePointer->getObject();

            if ($scopePointer instanceof ClassMethod) {
                if (count($scopePointer->getParams()) === 0) {
                    $context->notice(
                        'fcall.argumentunpacking',
                        sprintf('Please use argument unpacking (...) instead of func_get_args().'),
                        $funcCall
                    );
                    return;
                }
            }
        }
    }
}
