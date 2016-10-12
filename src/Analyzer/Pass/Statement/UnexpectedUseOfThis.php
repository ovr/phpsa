<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class UnexpectedUseOfThis implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for behavior that would result in overwriting $this variable.';

    /**
     * @param Node\Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Node\Stmt $stmt, Context $context)
    {
        if ($stmt instanceof Stmt\ClassMethod || $stmt instanceof Stmt\Function_) {
            return $this->inspectParams($stmt, $context);
        } elseif ($stmt instanceof Stmt\TryCatch) {
            return $this->inspectTryCatch($stmt, $context);
        } elseif ($stmt instanceof Stmt\Foreach_) {
            return $this->inspectForeach($stmt, $context);
        } elseif ($stmt instanceof Stmt\Static_) {
            return $this->inspectStaticVar($stmt, $context);
        } elseif ($stmt instanceof Stmt\Global_) {
            return $this->inspectGlobalVar($stmt, $context);
        } elseif ($stmt instanceof Stmt\Unset_) {
            return $this->inspectUnset($stmt, $context);
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Stmt\ClassMethod::class,
            Stmt\Function_::class,
            Stmt\TryCatch::class,
            Stmt\Foreach_::class,
            Stmt\Static_::class,
            Stmt\Global_::class,
            Stmt\Unset_::class,
        ];
    }

    /**
     * @param Stmt\ClassMethod|Stmt\Function_ $stmt
     * @param Context $context
     * @return bool
     */
    private function inspectParams(Stmt $stmt, Context $context)
    {
        /** @var \PhpParser\Node\Param $param */
        foreach ($stmt->getParams() as $param) {
            if ($param->name === 'this') {
                $context->notice(
                    'unexpected_use.this',
                    sprintf('Method/Function %s can not have a parameter named "this".', $stmt->name),
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
