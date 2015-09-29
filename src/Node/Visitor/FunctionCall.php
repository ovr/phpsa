<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 12.09.15
 * Time: 13:12
 */

namespace PHPSA\Node\Visitor;

use PhpParser\Node;
use PHPSA\Analyzer\Pass\FunctionCall\RandomApiMigration;

class FunctionCall extends \PhpParser\NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        if ($node instanceof \PhpParser\Node\Expr\FuncCall) {
            $examplePass = new RandomApiMigration();
            $examplePass->visitPhpFunctionCall($node);
        }
    }
}
