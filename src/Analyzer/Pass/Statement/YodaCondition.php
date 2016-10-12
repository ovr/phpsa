<?php
/**
 * @author Alexey Kolpakov https://github.com/alhimik45 <alhimik45@gmail.com>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class YodaCondition implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for Yoda conditions, where a constant is placed before the variable. For example: `if (3 == $a)`';

    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        $condition = $stmt->cond;

        if ($stmt instanceof Stmt\For_ && count($stmt->cond) > 0) { // For is the only one that has an array as condition
            $condition = $condition[0];
        }

        if ($condition instanceof Equal ||
            $condition instanceof NotEqual ||
            $condition instanceof Identical ||
            $condition instanceof NotIdentical
        ) {
            if ($condition->left instanceof Scalar &&
                $condition->right instanceof Variable) {
                $context->notice(
                    'yoda_condition',
                    'Avoid Yoda conditions, where constants are placed first in comparisons',
                    $stmt
                );
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Stmt\If_::class,
            Stmt\ElseIf_::class,
            Stmt\For_::class,
            Stmt\While_::class,
            Stmt\Do_::class,
            Stmt\Case_::class,
        ];
    }
}
