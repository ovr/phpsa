<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ClassConstFetch extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\ClassConstFetch';

    /**
     * classname::class, classname::CONSTANTNAME, ...
     *
     * @param \PhpParser\Node\Expr\ClassConstFetch $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($expr->name == "class") {
            // @todo return fully qualified classname
            return new CompiledExpression();
        }

        $leftCE = $compiler->compile($expr->class);
        if ($leftCE->isObject()) {
            $leftCEValue = $leftCE->getValue();
            if ($leftCEValue instanceof ClassDefinition) {
                if (!$leftCEValue->hasConst($expr->name, true)) {
                    $context->notice(
                        'undefined-const',
                        sprintf('Constant %s does not exist in %s scope', $expr->name, $expr->class),
                        $expr
                    );
                    return new CompiledExpression(CompiledExpression::UNKNOWN);
                }

                return new CompiledExpression();
            }
        }

        $context->debug('Unknown const fetch', $expr);
        return new CompiledExpression();
    }
}
