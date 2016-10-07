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

class StaticPropertyFetch extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\StaticPropertyFetch';

    /**
     * {expr}::${expr};
     *
     * @param \PhpParser\Node\Expr\StaticPropertyFetch $expr
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
            if (!$classDefinition->hasProperty($name, true)) {
                $context->notice(
                    'undefined-scall',
                    sprintf('Static property $%s does not exist in %s scope', $name, $expr->class),
                    $expr
                );

                return new CompiledExpression();
            }

            $property = $classDefinition->getPropertyStatement($name, true);
            if (!$property->isStatic()) {
                $context->notice(
                    'undefined-scall',
                    sprintf('Property $%s is not static but was called in a static way', $name),
                    $expr
                );
            }

            return $expressionCompiler->compile($property);
        }

        $context->debug('Unknown static property fetch', $expr);
        return new CompiledExpression();
    }
}
