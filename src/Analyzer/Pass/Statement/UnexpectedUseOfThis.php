<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class UnexpectedUseOfThis implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param Node\Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Node\Stmt $stmt, Context $context)
    {
        $result = false;

        if ($stmt instanceof Stmt\ClassMethod) {
            $result = $result || $this->inspectClassMethodArguments($stmt, $context);
        }

        if ($stmt instanceof Stmt\TryCatch) {
            $result = $result || $this->inspectTryCatch($stmt, $context);
        }

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
            Stmt\ClassMethod::class,
            Stmt\TryCatch::class,
        ];
    }

    /**
     * @param Stmt\ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    private function inspectClassMethodArguments(Stmt\ClassMethod $methodStmt, Context $context)
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

    /**
     * @param Stmt\TryCatch $tryCatchStmt
     * @param Context $context
     * @return bool
     */
    private function inspectTryCatch(Stmt\TryCatch $tryCatchStmt, Context $context)
    {
        $result = false;

        /** @var Stmt\Catch_ $catch */
        foreach ($tryCatchStmt->catches as $catch) {
            if ($catch->var === 'this') {
                $result = true;
                $context->notice(
                    'this.catch_variable',
                    'Catch block can not have a catch variable named "this".',
                    $catch
                );
            }
        }

        return $result;
    }
}
