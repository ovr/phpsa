<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Bitwise;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class BitwiseNotTest extends AbstractUnaryOp
{
    /**
     * @param $a
     * @return array
     */
    protected function process($a)
    {
        return ~$a;
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
        ];
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\BitwiseNot
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\BitwiseNot($a);
    }
}
