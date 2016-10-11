<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\Analyzer\Pass;
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
        if ($this->returnStatementExists($stmt) && $this->yieldStatementExists($stmt)) {
            $context->notice('return_and_yield_in_one_method', 'Do not use return and yield in a one method', $stmt);
            return true;
        }

        return false;
    }

    /**
     * @param Stmt $node
     *
     * @return bool
     */
    private function returnStatementExists(Stmt $node)
    {
        return (bool)$this->findReturnStatement([$node])->current();
    }

    /**
     * @param Stmt $node
     *
     * @return bool
     */
    private function yieldStatementExists(Stmt $node)
    {
        return (bool)$this->findStatement([$node], Yield_::class)->current();
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
