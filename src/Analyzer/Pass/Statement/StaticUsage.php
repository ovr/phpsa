<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Static_;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class StaticUsage implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of static variables (not properties).';

    /**
     * @param Static_ $stmt
     * @param Context $context
     *
     * @return bool
     */
    public function pass(Static_ $stmt, Context $context)
    {
        $context->notice(
            'static_usage',
            'Do not use static variable scoping',
            $stmt
        );

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Static_::class,
        ];
    }
}
