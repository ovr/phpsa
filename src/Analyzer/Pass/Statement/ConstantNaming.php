<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassConst;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Pass\ConfigurablePassInterface;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ConstantNaming implements ConfigurablePassInterface, AnalyzerPassInterface
{
    /**
     * @param ClassConst $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassConst $stmt, Context $context)
    {
        $result = false;
        foreach ($stmt->consts as $const) {
            if ($const->name !== strtoupper($const->name)) {
                $context->notice(
                    'constant.naming',
                    'Constant names should be all uppercase.',
                    $stmt
                );

                $result = true;
            }
        }
        
        return $result;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('constant_naming')
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
            ClassConst::class
        ];
    }
}
