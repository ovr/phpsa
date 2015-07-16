<?php

namespace PHPSA\Visitor\Expression;

use Ovr\PHPReflection\Reflector;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;

class FunctionCall extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\FuncCall';

    /**
     * @param \PhpParser\Node\Expr\FuncCall $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        $name = $expr->name->parts[0];
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

        if (!$functionDefinition && !$exists) {
            $reflector = new Reflector(Reflector::manuallyFactory());
            $functionReflection = $reflector->getFunction($name);
            if ($functionReflection) {
                return new CompiledExpression($functionReflection->getReturnType(), null);
            }
        }

        if (!$exists) {
            $context->notice(
                'undefined-fcall',
                sprintf('Function %s() is not exists', $expr->name->parts[0]),
                $expr
            );
        }

        return new CompiledExpression();
    }
}
