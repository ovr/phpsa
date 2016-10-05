<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PhpParser\Node\Stmt\Goto_;
use PhpParser\Node\Stmt\Label;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DoNotUseGoto implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        if ($stmt instanceof Label) {
            $context->notice(
                'do_not_use_goto',
                'Do not use labels',
                $stmt
            );
            return true;
        } elseif ($stmt instanceof Goto_) {
            $context->notice(
                'do_not_use_goto',
                'Do not use goto statements',
                $stmt
            );
            return true;
        }
        return false;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('do_not_use_goto')
            ->canBeDisabled();

        return $treeBuilder;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Goto_::class,
            Label::class,
        ];
    }
}
