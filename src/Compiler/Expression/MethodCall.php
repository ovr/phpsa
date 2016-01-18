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
        $expressionCompiler = new Expression($context);
        $methodNameCE = $expressionCompiler->compile($expr->var);

        $leftCE = $expressionCompiler->compile($expr->var);
        $leftSymbol = $leftCE->getVariable();
        if ($leftSymbol) {
            if ($leftCE->isObject()) {
                /** @var ClassDefinition $calledObject */
                $calledObject = $leftSymbol->getValue();
                if ($calledObject instanceof ClassDefinition) {
                    $methodName = $methodNameCE->isString() ? $methodNameCE->getValue() : false;
                    if ($methodName) {
                        if (!$calledObject->hasMethod($methodName, true)) {
                            $context->notice(
                                'undefined-mcall',
                                sprintf('Method %s() does not exist in %s scope', $methodName, $leftCE->getValue()),
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
                                'undefined-mcall',
                                sprintf('Method %s() is a static function but called like class method in $%s variable', $methodName, $expr->var->name),
                                $expr
                            );
                        }

                        return $method->run($context, $expr->args);
                    }

                    return new CompiledExpression;
                }

                /**
                 * It's a wrong type or value, maybe it's implemented and We need to fix it in another compilers
                 */
                $context->debug('Unknown $calledObject - is ' . gettype($calledObject));
                return new CompiledExpression();
            } elseif (!$leftCE->canBeObject()) {
                $context->notice(
                    'variable-wrongtype.mcall',
                    sprintf('Variable $%s is not object\\callable and cannot be called like this', $methodNameCE->getValue()),
                    $expr,
                    Check::CHECK_ALPHA
                );
            }

            return new CompiledExpression;
        }

        $context->notice(
            'undefined-variable.mcall',
            sprintf('Variable $%s is not defined in this scope', $methodNameCE->getValue()),
            $expr
        );

        return new CompiledExpression();
    }
}
