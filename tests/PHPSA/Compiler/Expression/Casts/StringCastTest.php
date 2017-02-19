<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class StringCastTest extends AbstractUnaryOp
{
    /**
     * @param $a
     * @return string
     */
    protected function process($a)
    {
        return (string) $a;
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
     * @return Node\Expr\Cast\String_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\String_($a);
    }
}
