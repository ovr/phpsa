<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;

class MethodCall extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\MethodCall';

    /**
     * @param \PhpParser\Node\Expr\MethodCall $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        if ($expr->var instanceof \PhpParser\Node\Expr\Variable) {
            if ($expr->var->name == 'this') {
                if (!$context->scope->hasMethod($expr->name)) {
                    $context->notice(
                        'undefined-mcall',
                        sprintf('Method %s() does not exist in %s scope', $expr->name, $expr->var->name),
                        $expr
                    );
                }

                return new CompiledExpression();
            }

            $symbol = $context->getSymbol($expr->var->name);
            if ($symbol) {
                switch ($symbol->getType()) {
                    case CompiledExpression::OBJECT:
                    case CompiledExpression::DYNAMIC:
                        return new CompiledExpression();
                        break;
                }

                $context->notice(
                    'undefined-variable.mcall',
                    sprintf('Variable %s() is not object\\callable', $expr->var->name),
                    $expr
                );
                return new CompiledExpression();
            } else {
                $context->debug('Unknown method call - undefined variable');
                return new CompiledExpression();
            }
        }

        $expression = new Expression($context);
        $expression->compile($expr->var);

        $context->debug('Unknown method call');
        return new CompiledExpression();
    }
}
