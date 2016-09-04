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

        if ($stmt instanceof Stmt\Foreach_) {
            $result = $result || $this->inspectForeach($stmt, $context);
        }

        if ($stmt instanceof Stmt\Static_) {
            $result = $result || $this->inspectStaticVar($stmt, $context);
        }

        if ($stmt instanceof Stmt\Global_) {
            $result = $result || $this->inspectGlobalVar($stmt, $context);
        }

        if ($stmt instanceof Stmt\Unset_) {
            $result = $result || $this->inspectUnset($stmt, $context);
        }

        return $result;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('unexpected_use.this')
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
            Stmt\Foreach_::class,
            Stmt\Static_::class,
            Stmt\Global_::class,
            Stmt\Unset_::class,
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
                    'unexpected_use.this',
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
                    'unexpected_use.this',
                    'Catch block can not have a catch variable named "this".',
                    $catch
                );
            }
        }

        return $result;
    }

    /**
     * @param Stmt\Foreach_ $foreachStmt
     * @param Context $context
     * @return bool
     */
    private function inspectForeach(Stmt\Foreach_ $foreachStmt, Context $context)
    {
        if ($foreachStmt->valueVar->name === 'this') {
            $context->notice(
                'unexpected_use.this',
                'Foreach loop can not use a value variable named "this".',
                $foreachStmt->valueVar
            );

            return true;
        }

        return false;
    }

    /**
     * @param Stmt\Static_ $staticStmt
     * @param Context $context
     * @return bool
     */
    private function inspectStaticVar(Stmt\Static_ $staticStmt, Context $context)
    {
        $result = false;

        /** @var Stmt\StaticVar $var */
        foreach ($staticStmt->vars as $var) {
            if ($var->name === 'this') {
                $result = true;

                $context->notice(
                    'unexpected_use.this',
                    'Can not declare a static variable named "this".',
                    $var
                );
            }
        }

        return $result;
    }

    /**
     * @param Stmt\Global_ $globalStmt
     * @param Context $context
     * @return bool
     */
    private function inspectGlobalVar(Stmt\Global_ $globalStmt, Context $context)
    {
        $result = false;

        foreach ($globalStmt->vars as $var) {
            if ($var->name === 'this') {
                $result = true;

                $context->notice(
                    'unexpected_use.this',
                    'Can not declare a global variable named "this".',
                    $var
                );
            }
        }

        return $result;
    }

    /**
     * @param Stmt\Unset_ $unsetStmt
     * @param Context $context
     * @return bool
     */
    private function inspectUnset(Stmt\Unset_ $unsetStmt, Context $context)
    {
        $result = false;

        foreach ($unsetStmt->vars as $var) {
            if ($var->name === 'this') {
                $result = true;

                $context->notice(
                    'unexpected_use.this',
                    'Can not unset $this.',
                    $var
                );
            }
        }

        return $result;
    }
}
