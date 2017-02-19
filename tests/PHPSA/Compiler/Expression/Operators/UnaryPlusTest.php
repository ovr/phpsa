<?php

namespace Tests\PHPSA\Compiler\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class UnaryPlusTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class UnaryPlusTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProviderForSuccess()
    {
        return [
            [-1],
            [1],
            [true],
            [false],
            ["test string"],
            ["10test string"],
            [""],
            [null],
        ];
    }

    /**
     * @dataProvider getDataProviderForSuccess
     */
    public function testSuccessFromDataProvider($value)
    {
        $baseExpression = new Node\Expr\UnaryPlus(
            $this->newScalarExpr($value)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        self::assertSame(+ (int) $value, $compiledExpression->getValue());
    }

    /**
     * @return array
     */
    public function getDataProviderForUnsupported()
    {
        return [
            [[]],
        ];
    }

    /**
     * @dataProvider getDataProviderForUnsupported
     */
    public function testUnsupportedOperandType($value)
    {
        $baseExpression = new Node\Expr\UnaryPlus(
            $this->newScalarExpr($value)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        self::assertSame(null, $compiledExpression->getValue());
    }
}
