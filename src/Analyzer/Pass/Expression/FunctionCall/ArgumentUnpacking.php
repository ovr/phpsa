<?php

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;
use PHPSA\Definition\ClassMethod;
use PHPSA\Definition\FunctionDefinition;

class ArgumentUnpacking extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of `func_get_args()` and suggests the use of argument unpacking. (... operator)';

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);

        if ($functionName !== 'func_get_args') {
            return;
        }

        $scopePointer = $context->scopePointer->getObject();

        if ($scopePointer instanceof ClassMethod || $scopePointer instanceof FunctionDefinition) {
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

    /**
     * {@inheritdoc}
     */
    public static function getMetadata()
    {
        $metaData = parent::getMetadata();
        $metaData->setRequiredPhpVersion('5.6');

        return $metaData;
    }
}
