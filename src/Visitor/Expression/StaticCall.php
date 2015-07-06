<?php

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
    public function compile($expr, Context $context)
    {
        if ($expr->class instanceof \PhpParser\Node\Name) {
            $scope = $expr->class->parts[0];
            $name = $expr->name;

            $error = false;

            if ($scope == 'self') {
                if (!$context->scope->hasMethod($name)) {
                    $error = true;
                } else {
                    $method = $context->scope->getMethod($name);
                    if (!$method->isStatic()) {
                        $error = true;
                    }
                }
            }

            if ($error) {
                $context->notice(
                    'undefined-scall',
                    sprintf('Static method %s() is not exists on %s scope', $name, $scope),
                    $expr
                );

                return new CompiledExpression();
            }

            return new CompiledExpression();
        }

        $context->debug('Unknown static function call');
        return new CompiledExpression();
    }
}
