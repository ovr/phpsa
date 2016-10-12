<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class ExitUsage implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of `exit()` and `die()`.';

    /**
     * @param Expr\Exit_ $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Exit_ $expr, Context $context)
    {
        $context->notice(
            'exit_usage',
            'exit/die statements make the code hard to test and should not be used',
            $expr
        );

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Expr\Exit_::class,
        ];
    }
}
