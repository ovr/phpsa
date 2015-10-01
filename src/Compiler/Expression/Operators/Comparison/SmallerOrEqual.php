<?php

namespace PHPSA\Compiler\Expression\Operators\Comparison;

class SmallerOrEqual extends AbstractOperator
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\SmallerOrEqual';

    /**
     * {expr} <= {expr}
     *
     * @param $left
     * @param $right
     * @return bool
     */
    public function compare($left, $right)
    {
        return $left <= $right;
    }
}
