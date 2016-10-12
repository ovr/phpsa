<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class EvalUsage implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of `eval()`.';

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
            Expr\Eval_::class
        ];
    }
}
