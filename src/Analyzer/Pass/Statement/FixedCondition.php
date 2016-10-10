<?php
/**
 * @author Christian Kraus <hanzi@hanzi.cc>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\While_;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class FixedCondition implements Pass\AnalyzerPassInterface
{
    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        $result = false;
        $expression = $context->getExpressionCompiler()->compile($stmt->cond);

        if ($expression->hasValue()) {
            $context->notice(
                'fixed_condition',
                'The condition will always result in the same boolean value',
                $stmt
            );

            $result = true;
        }

        if ($stmt instanceof If_) {
            foreach ($stmt->elseifs as $elseif) {
                $result = $this->pass($elseif, $context) || $result;
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
            If_::class,
            While_::class,
            Do_::class,
            Switch_::class,
        ];
    }
}
