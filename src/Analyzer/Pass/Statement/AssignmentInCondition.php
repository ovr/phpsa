<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\Stmt\For_;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class AssignmentInCondition implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for assignments in conditions. (= instead of ==)';

    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        $condition = $stmt->cond;

        if ($stmt instanceof For_ && count($stmt->cond) > 0) { // For is the only one that has an array as condition
            $condition = $condition[0];
        }

        if ($condition instanceof Assign) {
            $context->notice(
                'assignment_in_condition',
                'An assignment statement has been made instead of conditional statement',
                $stmt
            );
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
            If_::class,
            ElseIf_::class,
            For_::class,
            While_::class,
            Do_::class,
            Case_::class,
        ];
    }
}
