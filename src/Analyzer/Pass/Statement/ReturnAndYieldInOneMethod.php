<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\Analyzer\Pass;
use PhpParser\Node\Expr;
use PHPSA\Context;

class ReturnAndYieldInOneMethod implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait,
        ResolveExpressionTrait;

    /**
     * @param Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt $stmt, Context $context)
    {
        $yieldExists = $this->findYieldExpression([$stmt])->current();
        if (!$yieldExists) {
            // YieldFrom is another expression
            $yieldExists = $this->findNode([$stmt], Expr\YieldFrom::class)->current();
        }

        if ($yieldExists && $this->findReturnStatement([$stmt])->current()) {
            $context->notice('return_and_yield_in_one_method', 'Do not use return and yield in a one method', $stmt);
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
