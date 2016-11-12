<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use PHPSA\Check;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;

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

        $compiledArguments = $this->parseArgs($expr->args, $context);

        $leftCE = $expressionCompiler->compile($expr->var);
        if ($leftCE->canBeObject()) {
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
                        return new CompiledExpression();
                    }

                    $method = $calledObject->getMethod($methodName, true);
                    if (!$method) {
                        $context->debug('getMethod is not working', $expr);
                        return new CompiledExpression();
                    }

                    if ($method->isStatic()) {
                        $context->notice(
                            'mcall.static',
                            "Method {$methodName}() is static but called like a class method",
                            $expr
                        );
                    }

                    return $method->run(clone $context, $compiledArguments);
                }
            }
            return new CompiledExpression();
        } else {
            $context->notice(
                'mcall.non-object',
                sprintf('$%s is not an object and cannot be called like this', $expr->var->name),
                $expr->var,
                Check::CHECK_ALPHA
            );
        }

        $context->debug('[Unknown] @todo MethodCall', $expr);
        return new CompiledExpression();
    }

    /**
     * @param \PhpParser\Node\Arg[] $arguments
     * @param Context $context
     * @return CompiledExpression[]
     */
    protected function parseArgs(array $arguments, Context $context)
    {
        $compiled = [];

        if ($arguments) {
            foreach ($arguments as $argument) {
                $compiled[] = $context->getExpressionCompiler()->compile($argument->value);
            }
        }

        return $compiled;
    }
}
