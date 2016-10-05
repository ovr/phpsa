<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DoNotUseLabels implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param Stmt\Label $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt\Label $stmt, Context $context)
    {
        $context->notice('do_not_use_labels', 'Do not use labels', $stmt);

        return true;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('do_not_use_labels')
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
            Stmt\Goto_::class,
            Stmt\Label::class,
        ];
    }
}
