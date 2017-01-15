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

class NewOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\New_';

    /**
     * @param \PhpParser\Node\Expr\New_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompile = $context->getExpressionCompiler();
        $expressionCompile->compile($expr->class);

        if (count($expr->args) > 0) {
            foreach ($expr->args as $argument) {
                $expressionCompile->compile($argument->value);
            }
        }

        $context->debug(
            '@todo We should support UnionTypes with FCQN assert...',
            $expr
        );

        return new CompiledExpression(
            CompiledExpression::OBJECT
        );
    }
}
