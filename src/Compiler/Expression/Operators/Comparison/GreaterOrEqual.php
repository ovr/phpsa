<?php

namespace PHPSA\Compiler\Expression\Operators\Comparison;

class GreaterOrEqual extends AbstractOperator
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\GreaterOrEqual';

    /**
     * {expr} >= {expr}
     *
     * @param $left
     * @param $right
     * @return bool
     */
    public function compare($left, $right)
    {
        return $left >= $right;
    }
}
