<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

/**
 * Class BooleanNotTest
 * @package Tests\PHPSA\Expression\Operators\Logical
 */
class BooleanNotTest extends AbstractUnaryOp
{
    /**
     * @param $a
     * @return array
     */
    protected function process($a)
    {
        return !$a;
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
     * @return Node\Expr\Cast\Bool_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\BooleanNot($a);
    }
}
