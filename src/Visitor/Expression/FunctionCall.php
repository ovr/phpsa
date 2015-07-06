<?php

namespace PHPSA\Visitor\Expression;

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
        if (!function_exists($expr->name->parts[0])) {
            $context->notice(
                'undefined-fcall',
                sprintf('Function %s() is not exists', $expr->name->parts[0]),
                $expr
            );

            return new CompiledExpression();
        }

        $reflector = new \Ovr\PHPReflection\Reflector(\Ovr\PHPReflection\Reflector::manuallyFactory());
        $functionReflection = $reflector->getFunction($expr->name->parts[0]);
        if ($functionReflection) {
            return new CompiledExpression($functionReflection->returnType, null);
        }

        return new CompiledExpression();
    }
}
