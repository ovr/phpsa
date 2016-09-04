<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class UnexpectedUseOfThis implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        $result = $this->inspectClassMethodArguments($methodStmt, $context);

        return $result;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('unexpected_use_of_this')
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
            ClassMethod::class
        ];
    }

    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    private function inspectClassMethodArguments(ClassMethod $methodStmt, Context $context)
    {
        /** @var \PhpParser\Node\Param $param */
        foreach ($methodStmt->getParams() as $param) {
            if ($param->name === 'this') {
                $context->notice(
                    'this.method_parameter',
                    sprintf('Method %s can not have a parameter named "this".', $methodStmt->name),
                    $param
                );

                return true;
            }
        }

        return false;
    }
}
