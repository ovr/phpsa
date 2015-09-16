<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;

class StaticCall extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\StaticCall';

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
                if (!$context->scope->hasMethod($name)) {
                    $context->notice(
                        'undefined-scall',
                        sprintf('Static method %s() does not exist in %s scope', $name, $scope),
                        $expr
                    );
                } else {
                    $method = $context->scope->getMethod($name);
                    if (!$method->isStatic()) {
                        $context->notice(
                            'undefined-scall',
                            sprintf('Method %s() is not static but it was called as static way', $name),
                            $expr
                        );
                    }
                }
            }

            return new CompiledExpression();
        }

        $context->debug('Unknown static function call');
        return new CompiledExpression();
    }
}
