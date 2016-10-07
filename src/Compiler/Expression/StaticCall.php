<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;

class StaticCall extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\StaticCall';

    /**
     * {expr}::{expr}();
     *
     * @param \PhpParser\Node\Expr\StaticCall $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompiler = $context->getExpressionCompiler();
        $leftCE = $expressionCompiler->compile($expr->class);

        if ($leftCE->isObject()) {
            $name = $expr->name;

            /** @var ClassDefinition $classDefinition */
            $classDefinition = $context->scope;
            if (!$classDefinition->hasMethod($name, true)) {
                $context->notice(
                    'undefined-scall',
                    sprintf('Static method %s() does not exist in %s scope', $name, $expr->class),
                    $expr
                );

                return new CompiledExpression();
            }

            $method = $classDefinition->getMethod($name, true);
            if ($expr->class->parts[0] !== 'parent' && !$method->isStatic()) {
                $context->notice(
                    'undefined-scall',
                    sprintf('Method %s() is not static but was called in a static way', $name),
                    $expr
                );
            }

            return new CompiledExpression();
        }

        $context->debug('Unknown static function call', $expr);
        return new CompiledExpression();
    }
}
