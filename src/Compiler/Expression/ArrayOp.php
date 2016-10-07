<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ArrayOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Array_';

    /**
     * [] array()
     *
     * @param \PhpParser\Node\Expr\Array_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($expr->items === []) {
            return new CompiledExpression(CompiledExpression::ARR, []);
        }

        $resultArray = [];

        foreach ($expr->items as $item) {
            $compiledValueResult = $compiler->compile($item->value);
            if ($item->key) {
                $compiledKeyResult = $compiler->compile($item->key);
                switch ($compiledKeyResult->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NULL:
                    case CompiledExpression::STRING:
                        $resultArray[$compiledKeyResult->getValue()] = $compiledValueResult->getValue();
                }
            } else {
                $resultArray[] = $compiledValueResult->getValue();
            }
        }

        return new CompiledExpression(CompiledExpression::ARR, $resultArray);
    }
}
