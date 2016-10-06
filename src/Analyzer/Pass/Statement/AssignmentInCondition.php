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
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\While_;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AssignmentInCondition implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        $result = false;

        if ($stmt instanceof If_) {
            $this->checkAssignment($stmt, $context);

            $elseifStmts = $stmt->elseifs;
            foreach ($elseifStmts as $elseif) {
                $result = $this->checkAssignment($elseif, $context) || $result;
            }
        } elseif ($stmt instanceof While_ || $stmt instanceof Do_) {
            $this->checkAssignment($stmt, $context);
        } elseif ($stmt instanceof Switch_) {
            $caseStmts = $stmt->cases;
            foreach ($caseStmts as $case) {
                $result = $this->checkAssignment($case, $context) || $result;
            }
        }

        return $result;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('assignment_in_condition')
            ->canBeDisabled();

        return $treeBuilder;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            If_::class,
            ElseIf_::class,
            While_::class,
            Do_::class,
            Switch_::class,
        ];
    }

    /**
     * @param Stmt $stmt
     * @param Context $context
     * @return bool
     */
    private function checkAssignment(Stmt $stmt, Context $context)
    {
        if ($stmt->cond instanceof Assign) {
            $context->notice(
                'assignment_in_condition',
                'An assignment statement has been made instead of conditional statement',
                $stmt
            );
            return true;
        }
    }
}
