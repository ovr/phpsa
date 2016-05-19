<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPSA\Context;

class MethodCannotReturn
{
    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        if (count($methodStmt->stmts) == 0) {
            return false;
        }

        $result = false;

        if ($methodStmt->name == '__construct' || $methodStmt->name == '__destruct') {
            foreach ($methodStmt->stmts as $stmt) {
                if ($stmt instanceof Return_) {
                    if (!$stmt->expr) {
                        continue;
                    }

                    $context->notice(
                        'return.construct',
                        sprintf('Method %s cannot return a value.', $methodStmt->name),
                        $stmt
                    );

                    $result = true;
                }
            }
        }

        return $result;
    }
}
