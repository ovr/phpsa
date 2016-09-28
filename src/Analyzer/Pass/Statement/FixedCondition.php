<?php
/**
 * @author Christian Kraus <hanzi@hanzi.cc>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\Stmt\For_;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class FixedCondition implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

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

        $expression = $context->getExpressionCompiler()->compile($condition);

        if ($expression->hasValue()) { // @todo implement isStatic() method to see if expression changes or not
            $context->notice(
                'fixed_condition',
                'The condition will always result in the same boolean value',
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
            While_::class,
            For_::class,
            Do_::class,
            Switch_::class,
        ];
    }
}
