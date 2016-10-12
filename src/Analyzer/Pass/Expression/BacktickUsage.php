<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class BacktickUsage implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of backtick operator for shell execution.';

    /**
     * @param Expr\ShellExec $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\ShellExec $expr, Context $context)
    {
        $context->notice(
            'backtick_usage',
            'It\'s bad practice to use the backtick operator for shell execution',
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
            Expr\ShellExec::class
        ];
    }
}
