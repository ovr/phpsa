<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;


abstract class BaseTestCase extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function smallerDataProvider()
    {
        return [
            [-1, -1],
            [-2, -1],
            [-3, -1],
            [-50, -1],
            [1, 2],
            [1, 5],
            [6, 5],
            [6, 6],
            [5, 6],
            [5, 5],
        ];
    }

    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    abstract protected function operator($a, $b);

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
}
