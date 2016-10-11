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
use PHPSA\Definition\ClosureDefinition;

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
        $name = $fNameExpression->getValue();

        $compiler = $context->application->compiler;
        $exists = false;
        $arguments = $this->parseArgs($expr, clone $context);

        // is it a Closure
        if ($fNameExpression->isCallable() && $name instanceof ClosureDefinition) {
            return $name->run($this->parseArgs($expr, clone $context), $context);
        }

        // is the function name a correct string
        if (!$fNameExpression->isString() || !$fNameExpression->isCorrectValue()) {
            $context->debug(
                'Unexpected function name type ' . $fNameExpression->getTypeName(),
                $expr->name
            );

            return new CompiledExpression();
        }

        // namespace check for correct functionDefinition
        if ($context->scope) {
            $functionDefinition = $compiler->getFunctionNS($name, $context->scope->getNamespace());
        } else {
            $functionDefinition = $compiler->getFunction($name);
        }

        // does the function exist
        if ($functionDefinition) {
            if (!$functionDefinition->isCompiled()) {
                $functionDefinition->compile(clone $context);
            }

            $exists = true;
        } else {
            $exists = function_exists($name);
        }

        if (!$exists) {
            $context->notice(
                'undefined-fcall',
                sprintf('Function %s() does not exist', $expr->name->parts[0]),
                $expr
            );
        } else {
            $reflector = new Reflector(Reflector::manuallyFactory());
            $functionReflection = $reflector->getFunction($name);
            if ($functionReflection) {
                $argumentsSuccessPass = $this->checkArguments($arguments, $functionReflection);

                // when everything is ok we run the function
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

        return new CompiledExpression();
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $expr
     * @return CompiledExpression[]
     */
    protected function parseArgs($expr, Context $context)
    {
        $arguments = [];

        foreach ($expr->args as $argument) {
            $arguments[] = $context->getExpressionCompiler()->compile($argument->value);
        }

        return $arguments;
    }

    protected function checkArguments(array $arguments, $functionReflection)
    {
        foreach ($arguments as $key => $argument) {
            $parameter = $functionReflection->getParameter($key);
            $paramType = $parameter->getType();
            $argumentType = $argument->getType();

            $numberTypes = [CompiledExpression::INTEGER, CompiledExpression::DOUBLE];
            $callableTypes = [CompiledExpression::STRING, CompiledExpression::ARR];

            // the paramtype is equal to the argument type or mixed
            // or paramtype is number and argumenttype is integer, double
            // or paramtype is callable and argumenttype is string, array
            if (!($paramType == $argumentType || $paramType == CompiledExpression::MIXED)
            && !($paramType == CompiledExpression::NUMBER && in_array($argumentType, $numberTypes))
            && !($paramType == CompiledExpression::CALLABLE_TYPE && in_array($argumentType, $callableTypes))) {
                return false;
            }
        }

        // argumentcount != paramcount
        if (count($arguments) != $functionReflection->getNumberOfRequiredParameters()) {
            return false;
        }

        return true;
    }
}
