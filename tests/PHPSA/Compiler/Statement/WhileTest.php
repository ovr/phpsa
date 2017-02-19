<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class WhileTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests while ($stmtTest = 2) {} creates the variable
     */
    public function testWhileConditionCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\While_(
            new Node\Expr\Assign(
                new Node\Expr\Variable(
                    new Node\Name("stmtTest")
                ),
                $this->newScalarExpr(2)
            )
        );
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        self::assertTrue($variable instanceof Variable);
    }

    /**
     * Tests while (1 == 1) { $stmtTest = 2; } creates the variable
     */
    public function testWhileStatementCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\While_(
            new Node\Expr\BinaryOp\Equal($this->newScalarExpr(1), $this->newScalarExpr(1)),
            ["stmts" =>
                new Node\Expr\Assign(
                    new Node\Expr\Variable(
                        new Node\Name("stmtTest")
                    ),
                    $this->newScalarExpr(2)
                )]
        );
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        self::assertTrue($variable instanceof Variable);
    }
}
