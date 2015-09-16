<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

/**
 * Class SmallerTest
 * @package Tests\PHPSA\Expression\Operators\Comparison
 *
 * @see PHPSA\Visitor\Expression\Operators\Comparison\Smaller
 */
class SmallerTest extends BaseTestCase
{
    /**
     * @param \PhpParser\Node\Scalar $a
     * @param \PhpParser\Node\Scalar $b
     * @return Node\Expr\BinaryOp\Smaller
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Smaller($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    protected function operator($a, $b)
    {
        return $a < $b;
    }
}
