<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

/**
 * Class EqualTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class EqualTest extends AbstractBinaryOp
{
    /**
     * @param $a
     * @param $b
     * @return bool
     */
    protected function process($a, $b)
    {
        return $a == $b;
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
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Equal
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Equal($a, $b);
    }
}
