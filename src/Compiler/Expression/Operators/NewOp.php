<?php
/**
 * PHP Static Analysis project 2015
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

            if (count($expr->args) > 0) {
                return new CompiledExpression(CompiledExpression::OBJECT);
            }

            if (class_exists($name, true)) {
                return new CompiledExpression(CompiledExpression::OBJECT, new $name());
            }

            return new CompiledExpression(CompiledExpression::OBJECT);
        }

        $this->context->debug('Unknown how to pass new');
        return new CompiledExpression();
    }
}
