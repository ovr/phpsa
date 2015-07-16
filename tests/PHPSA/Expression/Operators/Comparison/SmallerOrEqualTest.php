<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

/**
 * Class SmallerOrEqualTest
 * @package Tests\PHPSA\Expression\Operators\Comparison
 *
 * @see PHPSA\Visitor\Expression\Operators\Comparison\SmallerOrEqual
 */
class SmallerOrEqualTest extends BaseTestCase
{
    /**
     * @param $a
     * @param $b
     * @return Node\Expr\BinaryOp\SmallerOrEqual
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\SmallerOrEqual(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    protected function operator($a, $b)
    {
        return $a <= $b;
    }
}
