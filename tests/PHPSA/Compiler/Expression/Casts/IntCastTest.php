<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class IntCastTest extends AbstractUnaryOp
{
    /**
     * @param $a
     * @return int
     */
    protected function process($a)
    {
        return (int) $a;
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
            CompiledExpression::NULL,
        ];
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\Int_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Int_($a);
    }
}
