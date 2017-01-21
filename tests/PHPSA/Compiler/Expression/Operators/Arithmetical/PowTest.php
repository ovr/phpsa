<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class PowTest extends AbstractBinaryOp
{
    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function process($a, $b)
    {
        return $a ** $b;
    }

    /**
     * @return array
     */
    protected function getSupportedTypes()
    {
        return [
            CompiledExpression::INTEGER,
            CompiledExpression::DOUBLE,
            CompiledExpression::BOOLEAN,
        ];
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Pow
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Pow($a, $b);
    }
}
