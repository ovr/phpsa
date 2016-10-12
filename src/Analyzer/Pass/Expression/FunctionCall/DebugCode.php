<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class DebugCode extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of debug code and suggests to remove it.';

    protected $map = [
        'var_dump' => 'var_dump',
        'var_export' => 'var_export',
        'debug_zval_dump' => 'debug_zval_dump'
    ];

    /** @var DocBlockFactory */
    protected $docBlockFactory;

    public function __construct()
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if (!$functionName || !isset($this->map[$functionName])) {
            return false;
        }

        if ($funcCall->getDocComment()) {
            $phpdoc = $this->docBlockFactory->create($funcCall->getDocComment()->getText());
            if ($phpdoc->hasTag('expected')) {
                return false;
            }
        }

        $context->notice(
            'debug.code',
            sprintf('Function %s() is a debug function, please don`t use it in production.', $functionName),
            $funcCall
        );

        return true;
    }
}
