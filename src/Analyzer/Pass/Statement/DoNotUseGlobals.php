<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PhpParser\Node\Stmt\Global_;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DoNotUseGlobals implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        if ($stmt instanceof Global_) {
            $context->notice(
                'do_not_use_globals',
                'Do not use globals',
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
        $treeBuilder->root('do_not_use_globals')
            ->canBeDisabled();

        return $treeBuilder;
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
