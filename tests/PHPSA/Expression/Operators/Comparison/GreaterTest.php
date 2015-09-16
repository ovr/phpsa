<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class GreaterTest
 * @package Tests\PHPSA\Expression\Operators\Comparison
 *
 * @see PHPSA\Compiler\Expression\Operators\Comparison\Greater
 */
class GreaterTest extends BaseTestCase
{
    /**
     * @param \PhpParser\Node\Scalar $a
     * @param \PhpParser\Node\Scalar $b
     * @return Node\Expr\BinaryOp\Greater
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Greater($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    protected function operator($a, $b)
    {
        return $a > $b;
    }
}
