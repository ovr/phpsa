<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use PhpParser\Node\Expr\Variable;
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
        if ($expr->var instanceof Variable) {
            $symbol = $context->getSymbol($expr->var->name);
            if ($symbol) {
                switch ($symbol->getType()) {
                    case CompiledExpression::OBJECT:
                    case CompiledExpression::DYNAMIC:
                        $symbol->incUse();

                        /** @var ClassDefinition $calledObject */
                        $calledObject = $symbol->getValue();
                        if ($calledObject instanceof ClassDefinition) {
                            $methodName = is_string($expr->name) ? $expr->name : false;

                            if ($expr->name instanceof Variable) {
                                /**
                                 * @todo implement fetch from symbol table
                                 */
                                //$methodName = $expr->name->name;
                            }

                            if ($expr->args) {
                                foreach ($expr->args as $argument) {
                                    $expression = new Expression($context);
                                    $expression->compile($argument);
                                }
                            }

                            if ($methodName) {
                                if (!$calledObject->hasMethod($methodName, true)) {
                                    $context->notice(
                                        'undefined-mcall',
                                        sprintf('Method %s() does not exist in %s scope', $methodName, $expr->var->name),
                                        $expr
                                    );

                                    //it's needed to exit
                                    return new CompiledExpression;
                                }

                                $method = $calledObject->getMethod($methodName);
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

                                return new CompiledExpression;
                            }

                            return new CompiledExpression;
                        }

                        /**
                         * It's a wrong type or value, maybe it's implemented and We need to fix it in another compilers
                         */
                        $context->debug('Unknown $calledObject - is ' . gettype($calledObject));
                        return new CompiledExpression();
                }

                $context->notice(
                    'variable-wrongtype.mcall',
                    sprintf('Variable $%s is not object\\callable and cannot be called like this', $expr->var->name),
                    $expr
                );
                return new CompiledExpression();
            } else {
                $context->notice(
                    'undefined-variable.mcall',
                    sprintf('Variable $%s is not defined in this scope', $expr->var->name),
                    $expr
                );

                return new CompiledExpression();
            }
        }

        $expression = new Expression($context);
        $expression->compile($expr->var);

        $context->debug('Unknown method call');
        return new CompiledExpression();
    }
}
