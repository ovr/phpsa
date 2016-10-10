<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class IfTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests if ($stmtTest = 2) creates the variable
     */
    public function testIfConditionCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\If_(
            new Node\Expr\Assign(
                new Node\Expr\Variable(
                    new Node\Name("stmtTest")
                ),
                $this->newScalarExpr(2)
            )
        );
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        parent::assertTrue($variable instanceof Variable);
    }

    /**
     * Tests if (1 == 1) { $stmtTest = 2; } creates the variable
     */
    public function testIfStatementCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\If_(
            new Node\Expr\BinaryOp\Equal($this->newScalarExpr(1), $this->newScalarExpr(1)),
            ["stmts" =>
                [new Node\Expr\Assign(
                    new Node\Expr\Variable(
                        new Node\Name("stmtTest")
                    ),
                    $this->newScalarExpr(2)
                )]
            ]
        );
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        parent::assertTrue($variable instanceof Variable);
    }
}
