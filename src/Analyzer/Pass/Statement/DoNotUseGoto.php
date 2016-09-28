<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PHPSA\Analyzer\Pass;
use PHPSA\Compiler\Event\StatementBeforeCompile;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DoNotUseGoto implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param Stmt\Goto_ $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt\Goto_ $stmt, Context $context)
    {
        $context->notice('do_not_use_goto', 'Do not use goto statements', $stmt);

        return true;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('do_not_use_goto')
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
            [Stmt\Goto_::class, StatementBeforeCompile::EVENT_NAME]
        ];
    }
}
