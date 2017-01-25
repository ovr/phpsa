<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Variable as Variable;
use PhpParser\Node\Expr\Variable as VariableNode;

class CoalesceTest extends \Tests\PHPSA\TestCase
{
    public function testCoalesceVarInt()
    {
        $this->markTestSkipped('Unsupported now, because it is not possible to get good results');

        $context = $this->getContext();
        $context->addVariable(new Variable("name", 10, CompiledExpression::INTEGER));

        $variable = new VariableNode("name");
        $else = parent::newScalarExpr("else");

        $baseExpression = new Node\Expr\BinaryOp\Coalesce($variable, $else);
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        parent::assertSame(10, $compiledExpression->getValue());
    }

    public function testCoalesceVarNull()
    {
        $this->markTestSkipped('Unsupported now, because it is not possible to get good results');

        $context = $this->getContext();
        $context->addVariable(new Variable("name", null, CompiledExpression::NULL));

        $variable = new VariableNode(new Name("name"));
        $else = parent::newScalarExpr("else");

        $baseExpression = new Node\Expr\BinaryOp\Coalesce($variable, $else);
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        parent::assertSame("else", $compiledExpression->getValue());
    }

    public function testCoalesceVarNotExisting()
    {
        $this->markTestSkipped('Unsupported now, because it is not possible to get good results');
        
        $context = $this->getContext();

        $variable = new VariableNode(new Name("name"));
        $else = parent::newScalarExpr("else");

        $baseExpression = new Node\Expr\BinaryOp\Coalesce($variable, $else);
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        parent::assertSame("else", $compiledExpression->getValue());
    }
}
