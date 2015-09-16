<?php

namespace Tests\PHPSA\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class UnaryMinusTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class UnaryMinusTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProviderForSuccess()
    {
        return array(
            array(-1),
            array(1),
            array(true),
            array(false),
            array("test string"),
            array("10test string"),
            array(""),
            array(null),
        );
    }

    /**
     * @dataProvider getDataProviderForSuccess
     */
    public function testSuccessFromDataProvider($value)
    {
        $baseExpression = new Node\Expr\UnaryMinus(
            $this->newScalarExpr($value)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame(-$value, $compiledExpression->getValue());
    }

    /**
     * @return array
     */
    public function getDataProviderForUnsupported()
    {
        return array(
            array([]),
        );
    }

    /**
     * @dataProvider getDataProviderForUnsupported
     */
    public function testUnsupportedOperandType($value)
    {
        $baseExpression = new Node\Expr\UnaryMinus(
            $this->newScalarExpr($value)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
