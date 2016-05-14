<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;
use PHPSA\Compiler\Expression;

class InstanceOfOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Instanceof_';

    /**
     * $a instanceof Node
     * $expr->expr instance of $expr->class
     *
     * @param \PhpParser\Node\Expr\Instanceof_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $leftCompiledExpression = $context->getExpressionCompiler()->compile($expr->expr);
        $rightCompiledExpression = $context->getExpressionCompiler()->compile($expr->class);

        if ($leftCompiledExpression->isObject() && $rightCompiledExpression->isObject()) {
            $leftVariable = $leftCompiledExpression->getVariable();
            $rightVariable = $rightCompiledExpression->getVariable();

            /**
             * $a = new A();
             * $b = $a;
             *
             * $a instanceof $b
             */
            if ($leftVariable && $rightVariable) {
                if ($leftVariable->isReferenced() && $leftVariable->getReferencedTo() instanceof $rightVariable) {
                    return new CompiledExpression(CompiledExpression::BOOLEAN, true);
                }
            }
        }

        /**
         * Anyway this operator will return BOOLEAN
         */
        return new CompiledExpression(CompiledExpression::BOOLEAN);
    }
}
