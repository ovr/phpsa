<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
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
        if ($expr->class instanceof \PhpParser\Node\Name) {
            $scope = $expr->class->parts[0];
            $name = $expr->name;

            if ($scope == 'self') {
                if ($context->scope instanceof ClassDefinition) {
                    $context->notice(
                        'scall-self-not-context',
                        sprintf('No scope. You cannot call from %s out from class scope', $name, $scope),
                        $expr
                    );

                    return new CompiledExpression();
                }

                /** @var ClassDefinition $classDefinition */
                $classDefinition = $context->scope;
                if (!$classDefinition->hasMethod($name)) {
                    $context->notice(
                        'undefined-scall',
                        sprintf('Static method %s() does not exist in %s scope', $name, $scope),
                        $expr
                    );

                    return new CompiledExpression();
                }

                $method = $classDefinition->getMethod($name);
                if (!$method->isStatic()) {
                    $context->notice(
                        'undefined-scall',
                        sprintf('Method %s() is not static but it was called as static way', $name),
                        $expr
                    );

                    return new CompiledExpression();
                }
            }

            return new CompiledExpression();
        }

        $context->debug('Unknown static function call');
        return new CompiledExpression();
    }
}
