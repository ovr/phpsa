<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Visitor\Expression;

class MethodCall extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\MethodCall';

    /**
     * @param \PhpParser\Node\Expr\MethodCall $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        if ($expr->var instanceof \PhpParser\Node\Expr\Variable) {
            $symbol = $context->getSymbol($expr->var->name);
            if ($symbol) {
                switch ($symbol->getType()) {
                    case CompiledExpression::OBJECT:
                    case CompiledExpression::DYNAMIC:
                        $symbol->incUse();

                        /** @var ClassDefinition $calledObject */
                        $calledObject = $symbol->getValue();
                        if ($calledObject instanceof ClassDefinition) {
                            if (!$calledObject->hasMethod($expr->name)) {
                                $context->notice(
                                    'undefined-mcall',
                                    sprintf('Method %s() does not exist in %s scope', $expr->name, $expr->var->name),
                                    $expr
                                );
                            }

                            return new CompiledExpression();
                        }

                        /**
                         * It's a wrong type or value, maybe it's implemented and We need to fix it in another compilers
                         */
                        $context->debug('Unknown $calledObject - is ' . gettype($calledObject));
                        return new CompiledExpression();
                }

                $context->notice(
                    'variable-wrongtype.mcall',
                    sprintf('Variable %s is not object\\callable and cannot be called like this', $expr->var->name),
                    $expr
                );
                return new CompiledExpression();
            } else {
                $context->notice(
                    'undefined-variable.mcall',
                    sprintf('Variable %s is not defined in this scope', $expr->var->name),
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
