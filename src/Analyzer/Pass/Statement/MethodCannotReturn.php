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
     * @param ClassMethod $st
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $st, Context $context)
    {
        if (count($st->stmts) == 0) {
            return false;
        }

        $result = false;

        if ($st->name == '__construct' || $st->name == '__destruct') {
            foreach ($st->stmts as $stmt) {
                if ($stmt instanceof Return_) {
                    if (!$stmt->expr) {
                        continue;
                    }

                    $context->notice(
                        'return.construct',
                        sprintf('Method %s cannot return a value.', $st->name),
                        $stmt
                    );

                    $result = true;
                }
            }
        }

        return $result;
    }
}
