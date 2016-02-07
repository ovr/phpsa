<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 12.09.15
 * Time: 13:12
 */

namespace PHPSA\Node\Visitor;

use PhpParser\Node;
use PHPSA\Analyzer\Pass\FunctionCall\AliasCheck;
use PHPSA\Analyzer\Pass\FunctionCall\DebugCode;
use PHPSA\Analyzer\Pass\FunctionCall\RandomApiMigration;
use PHPSA\Analyzer\Pass\FunctionCall\UseCast;
use PHPSA\Context;

class FunctionCall extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof \PhpParser\Node\Expr\FuncCall) {
            $examplePass = new RandomApiMigration();
            $examplePass->visitPhpFunctionCall($node, $this->context);

            $examplePass = new DebugCode();
            $examplePass->visitPhpFunctionCall($node, $this->context);

            $examplePass = new UseCast();
            $examplePass->visitPhpFunctionCall($node, $this->context);

            $examplePass = new AliasCheck();
            $examplePass->visitPhpFunctionCall($node, $this->context);
        }
    }
}
