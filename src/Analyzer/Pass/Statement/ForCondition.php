<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\For_;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class ForCondition implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of `for` with multiple conditions.';

    /**
     * @param For_ $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(For_ $stmt, Context $context)
    {
        if (count($stmt->cond) > 1) {
            $context->notice(
                'for_condition',
                'You should merge the conditions into one with &&',
                $stmt
            );
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            For_::class,
        ];
    }
}
