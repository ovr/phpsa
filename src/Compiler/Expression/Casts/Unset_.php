<?php

namespace PHPSA\Compiler\Expression\Casts;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class Unset_ extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Cast\Unset_';

    /**
     * (unset) {$expr}
     *
     * @param \PhpParser\Node\Expr\Cast\Unset_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::NULL:
                $context->notice('stupid-cast', "You are trying to cast 'unset' to 'null'", $expr);
                return $compiledExpression;
        }

        return new CompiledExpression(CompiledExpression::NULL, null);
    }