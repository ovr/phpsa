<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use PhpParser\Node\Expr\Variable;
use PHPSA\Check;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Compiler\Expression;

class MethodCall extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\MethodCall';

    /**
     * @param \PhpParser\Node\Expr\MethodCall $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompiler = $context->getExpressionCompiler();
        $methodNameCE = $expressionCompiler->compile($expr->name);

        $leftCE = $expressionCompiler->compile($expr->var);
        if ($leftCE->isObject()) {
            /** @var ClassDefinition $calledObject */
            $calledObject = $leftCE->getValue();
            if ($calledObject instanceof ClassDefinition) {
                $methodName = $methodNameCE->isString() ? $methodNameCE->getValue() : false;
                if ($methodName) {
                    if (!$calledObject->hasMethod($methodName, true)) {
                        $context->notice(
                            'mcall.undefined',
                            sprintf('Method %s() does not exist in %s scope', $methodName, $calledObject->getName()),
                            $expr
                        );

                        //it's needed to exit
                        return new CompiledExpression;
                    }

                    $method = $calledObject->getMethod($methodName, true);
                    if (!$method) {
                        $context->debug('getMethod is not working');
                        return new CompiledExpression;
                    }

                    if ($method->isStatic()) {
                        $context->notice(
                            'mcall.static',
                            "Method {$methodName}() is a static function but called like class method",
                            $expr
                        );
                    }

                    return $method->run($context, $expr->args);
                }

                return new CompiledExpression;
            }
        } elseif (!$leftCE->canBeObject()) {
            $context->notice(
                'mcall.not-object',
                'Is not object cannot be called like this',
                $expr->var,
                Check::CHECK_ALPHA
            );
        }

        $context->debug('[Unknown] @todo MethodCall');
        return new CompiledExpression;
    }
}
