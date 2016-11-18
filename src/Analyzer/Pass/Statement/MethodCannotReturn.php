<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class MethodCannotReturn implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;
    use ResolveExpressionTrait;

    const DESCRIPTION = 'Checks for return statements in `__construct` and `__destruct` since they can\'t return anything.';

    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        if ($methodStmt->stmts === null) {
            return false;
        }
        
        if (count($methodStmt->stmts) == 0) {
            return false;
        }

        $result = false;

        if ($methodStmt->name == '__construct' || $methodStmt->name == '__destruct') {
            foreach ($this->findReturnStatement($methodStmt->stmts) as $returnStmt) {
                if (!$returnStmt->expr) {
                    continue;
                }

                $context->notice(
                    'return.construct',
                    sprintf('Method %s cannot return a value.', $methodStmt->name),
                    $returnStmt
                );

                $result = true;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            ClassMethod::class
        ];
    }
}
