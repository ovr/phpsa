<?php

namespace Tests\PHPSA\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class ModTest extends AbstractDivMod
{
    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function process($a, $b)
    {
        return $a % $b;
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Mod
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Mod($a, $b);
    }

    protected function getAssertType()
    {
        return CompiledExpression::INTEGER;
    }
}
