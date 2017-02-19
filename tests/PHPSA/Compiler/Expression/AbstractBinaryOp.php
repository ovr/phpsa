<?php

namespace Tests\PHPSA\Compiler\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

abstract class AbstractBinaryOp extends \Tests\PHPSA\TestCase
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
     * @param $b
     * @return Node\Expr
     */
    abstract protected function buildExpression($a, $b);

    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    abstract protected function process($a, $b);

    /**
     * @return array
     */
    abstract protected function getSupportedTypes();

    /**
     * Tests {left-expr} $operator {right-expr}
     */
    public function testOperatorCompile()
    {
        foreach ($this->getSupportedTypes() as $type1) {
            foreach ($this->getSupportedTypes() as $type2) {
                $baseExpression = $this->buildExpression(
                    $this->newScalarExpr($this->data[$type1]),
                    $this->newScalarExpr($this->data[$type2])
                );
                $compiledExpression = $this->compileExpression($baseExpression);

                $this->assertInstanceOfCompiledExpression($compiledExpression);
                //$this->assertSame($this->getExpressionType($a, $b), $compiledExpression->getType());
                $this->assertEquals($this->process($this->data[$type1], $this->data[$type2]), $compiledExpression->getValue());
            }
        }
    }

    /**
     * Tests {left-expr::UNKNOWN} $operator {right-expr}
     */
    public function testFirstArgUnexpectedType()
    {
        $baseExpression = $this->buildExpression(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} $operator {right-expr::UNKNOWN}
     */
    public function testSecondArgUnexpectedType()
    {
        $baseExpression = $this->buildExpression(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
