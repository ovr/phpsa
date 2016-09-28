<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Compiler\Event\ExpressionAfterCompile;
use PHPSA\Context;

class EvalUsage implements AnalyzerPassInterface
{
    /**
     * @param Expr\Eval_ $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Eval_ $expr, Context $context)
    {
        $context->notice(
            'eval_usage',
            'Using eval is discouraged.',
            $expr
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegister()
    {
        return [
            [Expr\Eval_::class, ExpressionAfterCompile::EVENT_NAME]
        ];
    }
}
