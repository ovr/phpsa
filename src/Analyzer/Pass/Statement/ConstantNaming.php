<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassConst;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ConstantNaming implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks that constants are all uppercase.';

    /**
     * @param ClassConst $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassConst $stmt, Context $context)
    {
        $result = false;

        foreach ($stmt->consts as $const) {
            if ($const->name != strtoupper($const->name)) {
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
     * @return array
     */
    public function getRegister()
    {
        return [
            ClassConst::class
        ];
    }
}
