<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class ForTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests for ($stmtTest = 2;;) {} creates the variable
     */
    public function testForInitCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\For_(
            ["init" =>
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

    /**
     * Tests for (;$stmtTest = 2;) {} creates the variable
     */
    public function testForConditionCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\For_(
            ["cond" =>
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

    /**
     * Tests for (;$stmtTest = 2) {} creates the variable
     */
    public function testForLoopCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\For_(
            ["loop" =>
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

    /**
     * Tests for (;;) { $stmtTest = 2 } creates the variable
     */
    public function testForStatementCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\For_(
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
