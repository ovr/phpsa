<?php

namespace PHPSA\Compiler\Expression\Operators\Comparison;

class Smaller extends AbstractOperator
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Smaller';

    /**
     * {expr} < {expr}
     *
     * @param $left
     * @param $right
     * @return bool
     */
    public function compare($left, $right)
    {
        return $left < $right;
    }
}
