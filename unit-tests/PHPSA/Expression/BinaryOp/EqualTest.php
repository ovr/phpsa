<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

/**
 * Class EqualTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class EqualTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function providerForStaticTrueEquals()
    {
        return array(
            array(-1, -1),
            array(-1, -1.0),
            array(-5, -5),
            array(-5, -5.0),
            array(-5.0, -5),
            array(-150, -150),
            array(-150.0, -150),
            array(-150, -150.0),
            array(150, 150),
            array(150, 150.0),
            array(150.0, 150),
            array(150.0, 150.0),
            array(true, true),
            array(true, 1),
            array(false, false),
            array(false, 0),
            array(0, false),
            array([], false),
            array(false, []),
            array([], []),
        );
    }

    /**
     * Tests {left-expr} == {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticTrueEquals
     */
    public function testStaticTrueEquals($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Equal(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }
}