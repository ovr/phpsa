<?php

namespace Tests\PHPSA\Compiler\Statement;

use PhpParser\Node;
use PHPSA\Variable;

class EchoTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests echo $stmtTest = 2; creates the variable
     */
    public function testEchoCreatesVar()
    {
        $context = $this->getContext();

        $statement = new Node\Stmt\Echo_([
            new Node\Expr\Assign(
                new Node\Expr\Variable(
                    new Node\Name("stmtTest")
                ),
                $this->newScalarExpr(2)
            )
        ]);
        
        \PHPSA\nodeVisitorFactory($statement, $context);

        $variable = $context->getSymbol("stmtTest");

        parent::assertTrue($variable instanceof Variable);
    }
}
