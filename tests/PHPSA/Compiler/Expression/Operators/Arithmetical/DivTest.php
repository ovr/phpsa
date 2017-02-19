<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class DivTest extends AbstractDivMod
{
    /**
     * @param $a
     * @param $b
     * @return float
     */
    protected function process($a, $b)
    {
        return $a / $b;
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Div
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Div($a, $b);
    }
}
