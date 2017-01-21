<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class ObjectCastTest extends AbstractUnaryOp
{
    /**
     * @param $a
     * @return object
     */
    protected function process($a)
    {
        return (object) $a;
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
     * @return Node\Expr\Cast\Object_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Object_($a);
    }
}
