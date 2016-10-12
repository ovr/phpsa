<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Check;
use PHPSA\Context;

class MagicMethodParameters implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks that magic methods have the right amount of parameters.';

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
                    'magic_method_parameters',
                    'Magic method __get must take 1 parameter at least',
                    $methodStmt,
                    Check::CHECK_SAFE
                );
            }
        }

        if ($methodStmt->name == '__set') {
            if (count($methodStmt->params) < 2) {
                $context->notice(
                    'magic_method_parameters',
                    'Magic method __set must take 2 parameters at least',
                    $methodStmt,
                    Check::CHECK_SAFE
                );
            }
        }

        if ($methodStmt->name == '__clone') {
            if (count($methodStmt->params) > 0) {
                $context->notice(
                    'magic_method_parameters',
                    'Magic method __clone cannot accept arguments',
                    $methodStmt,
                    Check::CHECK_SAFE
                );
            }
        }
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
}
