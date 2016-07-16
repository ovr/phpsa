<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Pass\ConfigurablePassInterface;
use PHPSA\Check;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class GetParametersCheck implements ConfigurablePassInterface, AnalyzerPassInterface
{
    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        if ($methodStmt->name == '__get') {
            if (count($methodStmt->params) == 0) {
                $context->notice(
                    'magic.get.wrong-parameters',
                    'Magic method __get must take 1 paramter at least',
                    $methodStmt,
                    Check::CHECK_SAFE
                );
            }
        }

        if ($methodStmt->name == '__set') {
            if (count($methodStmt->params) == 0) {
                $context->notice(
                    'magic.get.wrong-parameters',
                    'Magic method __set must take 1 paramter at least',
                    $methodStmt,
                    Check::CHECK_SAFE
                );
            }
        }

        if ($methodStmt->name == '__clone') {
            if (count($methodStmt->params) > 0) {
                $context->notice(
                    'magic.get.wrong-parameters',
                    'Magic method __clone cannot accept arguments',
                    $methodStmt,
                    Check::CHECK_SAFE
                );
            }
        }
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('method_cannot_return')
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
            \PhpParser\Node\Stmt\ClassMethod::class
        ];
    }
}
