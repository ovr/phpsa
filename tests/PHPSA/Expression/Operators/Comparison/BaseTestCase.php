<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

abstract class BaseTestCase extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function smallerDataProvider()
    {
        return array(
            array(-1, -1),
            array(-2, -1),
            array(-3, -1),
            array(-50, -1),
            array(1, 2),
            array(1, 5),
            array(6, 5),
            array(6, 6),
            array(5, 6),
            array(5, 5),
        );
    }

    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    abstract protected function operator($a, $b);

    /**
     * @param \PhpParser\Node\Scalar $a
     * @param \PhpParser\Node\Scalar $b
     * @return \PhpParser\Node\Expr\BinaryOp
     */
    abstract protected function buildExpression($a, $b);

    /**
     * Tests {int} $operator {int} = {int}
     *
     * @dataProvider smallerDataProvider
     */
    public function testSimpleSuccessCompile($a, $b)
    {
        $baseExpression = $this->buildExpression(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($this->operator($a, $b), $compiledExpression->getValue());
    }

    public function testFirstUnexpectedTypes()
    {
        $baseExpression = $this->buildExpression(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    public function testSecondUnexpectedTypes()
    {
        $baseExpression = $this->buildExpression(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
