<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DoNotUseInlineHTML implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param Stmt\InlineHTML $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt\InlineHTML $stmt, Context $context)
    {
        $context->notice('do_not_use_inline_html', 'Do not use inline HTML', $stmt);

        return true;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('do_not_use_inline_html')
            ->canBeDisabled()
        ;

        return $treeBuilder;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Stmt\InlineHTML::class,
        ];
    }
}
