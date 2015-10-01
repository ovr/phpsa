<?php

namespace PHPSA\Compiler\Expression\Operators\Comparison;

class Greater extends AbstractOperator
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Greater';

    /**
     * {expr} > {expr}
     *
     * @param $left
     * @param $right
     * @return bool
     */
    public function compare($left, $right)
    {
        return $left > $right;
    }
}
