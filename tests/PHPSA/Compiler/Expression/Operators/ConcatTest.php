<?php

namespace Tests\PHPSA\Compiler\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class ConcatTest extends AbstractBinaryOp
{
    /**
     * @param $a
     * @param $b
     * @return string
     */
    protected function process($a, $b)
    {
        return $a . $b;
    }

    /**
     * @return array
     */
    protected function getSupportedTypes()
    {
        return [
            CompiledExpression::INTEGER,
            CompiledExpression::DOUBLE,
            CompiledExpression::STRING,
            CompiledExpression::BOOLEAN,
        ];
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Concat
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Concat($a, $b);
    }
}
