<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PhpParser\Node\Stmt\Global_;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class GlobalUsage implements Pass\AnalyzerPassInterface
{
    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Global_ $stmt, Context $context)
    {
        $context->notice(
            'global_usage',
            'Do not use globals',
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
            Global_::class,
        ];
    }
}
