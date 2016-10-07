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
        if ($expr->class instanceof Node\Name) {
            $name = $expr->class->parts[0];

            $arguments = [];

            if (count($expr->args) > 0) {
                foreach ($expr->args as $argument) {
                    $arguments[] = $context->getExpressionCompiler()->compile($argument->value);
                }
            } else {
                if (class_exists($name, true)) {
                    return new CompiledExpression(CompiledExpression::OBJECT, new $name());
                }
            }

            return new CompiledExpression(CompiledExpression::OBJECT);
        }

        $context->debug('Unknown how to pass new', $expr);
        return new CompiledExpression();
    }
}
