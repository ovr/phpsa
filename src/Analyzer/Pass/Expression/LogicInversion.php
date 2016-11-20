<?php
/**
 * @author Medvedev Alexandr https://github.com/lexty <alexandr.mdr@gmail.com>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr\BooleanNot;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class LogicInversion implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for Logic inversion like `if (!($a == $b))` and suggests the correct operator.';

    /**
     * @var array
     */
    protected $map = [
        'Expr_BinaryOp_Equal' => ['!=', '=='],
        'Expr_BinaryOp_NotEqual' => ['==', '!='],
        'Expr_BinaryOp_Identical' => ['===', '!=='],
        'Expr_BinaryOp_NotIdentical' => ['!==', '==='],
        'Expr_BinaryOp_Greater' => ['>', '<='],
        'Expr_BinaryOp_GreaterOrEqual' => ['>=', '<'],
        'Expr_BinaryOp_Smaller' => ['<', '>='],
        'Expr_BinaryOp_SmallerOrEqual' => ['<=', '>'],
    ];

    /**
     * @param BooleanNot $expr
     * @param Context $context
     * @return bool
     */
    public function pass(BooleanNot $expr, Context $context)
    {
        if (!array_key_exists($expr->expr->getType(), $this->map)) {
            return false;
        }
        
        list($use, $instead) = $this->map[$expr->expr->getType()];
        $msg = sprintf('Use "a %s b" expression instead of "!(a %s b)".', $use, $instead);

        $context->notice('logic_inversion', $msg, $expr);

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            BooleanNot::class,
        ];
    }
}
