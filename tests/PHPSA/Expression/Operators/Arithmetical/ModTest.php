<?php

namespace Tests\PHPSA\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class ModTest extends AbstractDivMod
{
    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function process($a, $b)
    {
        return $a % $b;
    }

    /**
     * @param $a
     * @param $b
     * @return Node\Expr\BinaryOp\Mod
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Mod(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
    }

    protected function getAssertType()
    {
        return CompiledExpression::INTEGER;
    }
}
