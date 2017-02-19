<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class BitwiseXorTest extends AbstractBinaryOp
{
    /**
     * @param $a
     * @param $b
     * @return integer
     */
    protected function process($a, $b)
    {
        return $a ^ $b;
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
     * @return Node\Expr\AssignOp\BitwiseXor
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\BitwiseXor($a, $b);
    }
}
