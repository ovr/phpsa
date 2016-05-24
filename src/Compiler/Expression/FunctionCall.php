<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use Ovr\PHPReflection\Reflector;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;

class FunctionCall extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\FuncCall';

    /**
     * @param \PhpParser\Node\Expr\FuncCall $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompiler = $context->getExpressionCompiler();
        $fNameExpression = $expressionCompiler->compile($expr->name);

        if ($fNameExpression->isString() && $fNameExpression->isCorrectValue()) {
            $name = $fNameExpression->getValue();
        } else {
            $context->debug(
                'Unexpected function name type ' . $fNameExpression->getTypeName(),
                $expr->name
            );

            return new CompiledExpression;
        }

        $compiler = $context->application->compiler;

        $exists = false;
        $namespace = null;

        if ($context->scope) {
            $namespace = $context->scope->getNamespace();
        }

        if ($namespace === null) {
            $functionDefinition = $compiler->getFunction($name);
        } else {
            $functionDefinition = $compiler->getFunctionNS($name, $namespace);
        }

        if (!$functionDefinition) {
            $exists = function_exists($name);
        }

        if ($functionDefinition) {
            if (!$functionDefinition->isCompiled()) {
                $functionDefinition->compile(clone $context);
            }

            $exists = true;
        }

        $arguments = $this->parseArgs($expr, clone $context);

        if (!$functionDefinition) {
            $reflector = new Reflector(Reflector::manuallyFactory());
            $functionReflection = $reflector->getFunction($name);
            if ($functionReflection) {
                $argumentsSuccessPass = true;

                if (count($arguments) > 0) {
                    foreach ($arguments as $key => $argument) {
                        $parameter = $functionReflection->getParameter($key);
                        if (!$parameter) {
                            /**
                             * @todo Think a little bit more about it
                             */
                            continue;
                        }

                        switch ($parameter->getType()) {
                            case CompiledExpression::MIXED:
                                //continue
                                break;
                            case CompiledExpression::INTEGER:
                                switch ($argument->getType()) {
                                    case CompiledExpression::INTEGER:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::DOUBLE:
                                switch ($argument->getType()) {
                                    case CompiledExpression::DOUBLE:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::NUMBER:
                                switch ($argument->getType()) {
                                    case CompiledExpression::INTEGER:
                                    case CompiledExpression::STRING:
                                    case CompiledExpression::NUMBER:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::RESOURCE:
                                switch ($argument->getType()) {
                                    case CompiledExpression::RESOURCE:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::ARR:
                                switch ($argument->getType()) {
                                    case CompiledExpression::ARR:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::STRING:
                                switch ($argument->getType()) {
                                    case CompiledExpression::STRING:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::OBJECT:
                                switch ($argument->getType()) {
                                    case CompiledExpression::OBJECT:
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            case CompiledExpression::CALLABLE_TYPE:
                                switch ($argument->getType()) {
                                    case CompiledExpression::CALLABLE_TYPE:
                                        break;
                                    case CompiledExpression::STRING:
                                        /**
                                         * @todo We need additional check on it
                                         */
                                        break;
                                    /**
                                     * array($this, 'method')
                                     */
                                    case CompiledExpression::ARR:
                                        /**
                                         * @todo We need additional check on it
                                         */
                                        break;
                                    default:
                                        $argumentsSuccessPass = false;
                                        break;
                                }
                                break;
                            default:
                                $argumentsSuccessPass = false;
                                break;
                        }
                    }
                }

                if (count($arguments) < $functionReflection->getNumberOfRequiredParameters()) {
                    $argumentsSuccessPass = false;
                }

                if ($argumentsSuccessPass && $functionReflection->isRunnable()) {
                    array_walk(
                        $arguments,
                        function (&$item) {
                            /** @var CompiledExpression $item */
                            $item = $item->getValue();
                        }
                    );

                    return new CompiledExpression(
                        $functionReflection->getReturnType(),
                        $functionReflection->run($arguments)
                    );
                }

                return new CompiledExpression($functionReflection->getReturnType());
            }
        }

        if (!$exists) {
            $context->notice(
                'undefined-fcall',
                sprintf('Function %s() does not exist', $expr->name->parts[0]),
                $expr
            );
        }

        return new CompiledExpression();
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $expr
     * @return CompiledExpression[]
     */
    protected function parseArgs($expr, Context $context)
    {
        $arguments = array();

        foreach ($expr->args as $argument) {
            $arguments[] = $context->getExpressionCompiler()->compile($argument->value);
        }

        return $arguments;
    }
}
