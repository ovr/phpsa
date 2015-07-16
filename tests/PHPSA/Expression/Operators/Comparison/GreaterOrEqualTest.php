<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

/**
 * Class GreaterOrEqualTest
 * @package Tests\PHPSA\Expression\Operators\Comparison
 *
 * @see PHPSA\Visitor\Expression\Operators\Comparison\GreaterOrEqual
 */
class GreaterOrEqualTest extends BaseTestCase
{
    /**
     * @param $a
     * @param $b
     * @return Node\Expr\BinaryOp\GreaterOrEqual
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\GreaterOrEqual(
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
        return $a >= $b;
    }
}
