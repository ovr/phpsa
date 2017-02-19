<?php

namespace Tests\PHPSA\Compiler\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

abstract class AbstractUnaryOp extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for tests
     */
    public $data = [
        CompiledExpression::INTEGER => 6,
        CompiledExpression::DOUBLE => 2.5,
        CompiledExpression::STRING => "test",
        CompiledExpression::BOOLEAN => true,
        CompiledExpression::NULL => null,
    ];

    /**
     * @param $a
     * @return Node\Expr
     */
    abstract protected function buildExpression($a);

    /**
     * @param $a
     * @return mixed
     */
    abstract protected function process($a);

    /**
     * @return array
     */
    abstract protected function getSupportedTypes();

    /**
     * Tests $operator {expr}
     */
    public function testOperatorCompile()
    {
        foreach ($this->getSupportedTypes() as $type) {
            $baseExpression = $this->buildExpression(
                $this->newScalarExpr($this->data[$type])
            );
            $compiledExpression = $this->compileExpression($baseExpression);

            self::assertInstanceOfCompiledExpression($compiledExpression);
            //self::assertSame($this->getExpressionType($a), $compiledExpression->getType());
            self::assertEquals($this->process($this->data[$type]), $compiledExpression->getValue());
        }
    }

    /**
     * Tests $operator {expr::UNKNOWN}
     */
    public function testUnexpectedType()
    {
        $baseExpression = $this->buildExpression(
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        self::assertSame(null, $compiledExpression->getValue());
    }
}
