<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class ElseTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests if (1 == 2) { } else { $stmtTest = 2; } creates the variable
     */
    public function testIfElseCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\If_(
            new Node\Expr\BinaryOp\Equal($this->newScalarExpr(1), $this->newScalarExpr(2)),
            ["else" =>
                new Node\Stmt\Else_(
                    [new Node\Expr\Assign(
                        new Node\Expr\Variable(
                            new Node\Name("stmtTest")
                        ),
                        $this->newScalarExpr(2)
                    )]
                )
            ]
        );
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        parent::assertTrue($variable instanceof Variable);
    }
}
