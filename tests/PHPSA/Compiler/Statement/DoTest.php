<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class DoTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests do {} while ($stmtTest = 2) creates the variable
     */
    public function testDoConditionCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\Do_(
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
     * Tests do { $stmtTest = 2; } while (1 == 1) creates the variable
     */
    public function testDoStatementCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\Do_(
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
