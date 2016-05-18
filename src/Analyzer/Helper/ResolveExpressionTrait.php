<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Helper;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

trait ResolveExpressionTrait
{
    /**
     * @param FuncCall $funcCall
     * @param Context $context
     * @return string|bool
     * @throws \PHPSA\Exception\RuntimeException
     */
    public function resolveFunctionName(FuncCall $funcCall, Context $context)
    {
        $funcNameCompiledExpression = $context->getExpressionCompiler()->compile($funcCall->name);

        if ($funcNameCompiledExpression->isString() && $funcNameCompiledExpression->isCorrectValue()) {
            return $funcNameCompiledExpression->getValue();
        } else {
            $context->debug(
                'Unexpected function name type ' . $funcNameCompiledExpression->getType(),
                $funcCall->name
            );
        }

        return false;
    }
}
