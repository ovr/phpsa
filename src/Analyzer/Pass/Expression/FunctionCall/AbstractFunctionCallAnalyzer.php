<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */


namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;

abstract class AbstractFunctionCallAnalyzer implements PassFunctionCallInterface, AnalyzerPassInterface
{
    use ResolveExpressionTrait;

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            \PhpParser\Node\Expr\FuncCall::class
        ];
    }
}
