<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class GreaterOrEqualTest
 * @package Tests\PHPSA\Expression\Operators\Comparison
 *
 * @see PHPSA\Compiler\Expression\Operators\Comparison\GreaterOrEqual
 */
class GreaterOrEqualTest extends BaseTestCase
{
    /**
     * @param \PhpParser\Node\Scalar $a
     * @param \PhpParser\Node\Scalar $b
     * @return Node\Expr\BinaryOp\GreaterOrEqual
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\GreaterOrEqual($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    protected function operator($a, $b)
    {
        return $a >= $b;
    }
}
