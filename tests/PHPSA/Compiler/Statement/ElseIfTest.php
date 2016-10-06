<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class ElseIfTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests if (1 == 2) { } elseif( 1 == 1) { $stmtTest = 2; } creates the variable
     */
    public function testIfElseIfCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\If_(
            new Node\Expr\BinaryOp\Equal($this->newScalarExpr(1), $this->newScalarExpr(2)),
            ["elseifs" =>
                [new Node\Stmt\ElseIf_(
                    new Node\Expr\BinaryOp\Equal($this->newScalarExpr(1), $this->newScalarExpr(1)),
                    [new Node\Expr\Assign(
                        new Node\Expr\Variable(
                            new Node\Name("stmtTest")
                        ),
                        $this->newScalarExpr(2)
                    )]
                )]
            ]
        );
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        parent::assertTrue($variable instanceof Variable);
    }
}
