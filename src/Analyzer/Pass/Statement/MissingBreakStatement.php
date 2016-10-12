<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class MissingBreakStatement implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for a missing break or return statement in switch cases. Can ignore empty cases and the last case.';

    /**
     * @param Stmt\Switch_ $switchStmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt\Switch_ $switchStmt, Context $context)
    {
        $result = false;
        $caseStmts = $switchStmt->cases;

        if (count($caseStmts) < 2) {
            return $result;
        }

        array_pop($caseStmts); // the last case statement CAN have no "break" or "return"

        /** @var Stmt\Case_ $case */
        foreach ($caseStmts as $case) {
            $result = $this->checkCaseStatement($case, $context) || $result;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Stmt\Switch_::class
        ];
    }

    /**
     * @param Stmt\Case_ $case
     * @param Context $context
     * @return bool
     */
    private function checkCaseStatement(Stmt\Case_ $case, Context $context)
    {
        /*
         * switch(…) {
         *     case 41:
         *     case 42:
         *     case 43:
         *         return 'the truth, or almost.';
         * }
         */
        if (!$case->stmts) {
            return false;
        }

        foreach ($case->stmts as $node) {
            // look for a break statement
            if ($node instanceof Stmt\Break_) {
                return false;
            }

            // or for a return
            if ($node instanceof Stmt\Return_) {
                return false;
            }
        }

        $context->notice(
            'missing_break_statement',
            'Missing "break" statement',
            $case
        );

        return true;
    }
}
