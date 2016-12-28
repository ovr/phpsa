<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class ReturnAndYieldInOneMethod implements Pass\AnalyzerPassInterface
{
    const DESCRIPTION = 'Checks for using return and yield statements in a one method and discourages it.';

    use DefaultMetadataPassTrait;
    use ResolveExpressionTrait;

    /**
     * @param Node\FunctionLike $func
     * @param Context $context
     * @return bool
     */
    public function pass(Node\FunctionLike $func, Context $context)
    {
        $stmts = $func->getStmts();
        if ($stmts === null) {
            return false;
        }
        $yieldExists = \PHPSA\generatorHasValue($this->findYieldExpression($stmts));
        if (!$yieldExists) {
            // YieldFrom is another expression
            $yieldExists = \PHPSA\generatorHasValue($this->findNode($stmts, Expr\YieldFrom::class));
        }

        if ($yieldExists && \PHPSA\generatorHasValue($this->findReturnStatement($stmts))) {
            $context->notice('return_and_yield_in_one_method', 'Do not use return and yield in a one method', $func);
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Stmt\ClassMethod::class,
        ];
    }
}
