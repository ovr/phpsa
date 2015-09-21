<?php

namespace Tests\PHPSA\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Variable;

/**
 * Class PostDecTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class PostDecTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProviderForSuccess()
    {
        return array(
            array(-1),
            array(1),
            array(99),
            array(0),
        );
    }

    /**
     * @dataProvider getDataProviderForSuccess
     */
    public function testSuccessFromDataProvider($value)
    {
        $testVariableName = 'myTestVariable';

        $context = $this->getContext();
        $context->addVariable(new Variable($testVariableName, $value, CompiledExpression::INTEGER));

        $baseExpression = new Node\Expr\PostDec(
            new Node\Expr\Variable(
                new Node\Name($testVariableName)
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame(--$value, $compiledExpression->getValue());
    }
}
