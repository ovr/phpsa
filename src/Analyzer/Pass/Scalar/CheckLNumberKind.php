<?php

namespace PHPSA\Analyzer\Pass\Scalar;

use PhpParser\Node\Scalar;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class CheckLNumberKind implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Using octal, hexadecimal or binary integers is discouraged.';

    /**
     * @param Scalar\LNumber $lNum
     * @param Context $context
     * @return bool
     */
    public function pass(Scalar\LNumber $lNum, Context $context)
    {
        if ($lNum->getAttribute('kind') != Scalar\LNumber::KIND_DEC) {
            $context->notice(
                'l_number_kind',
                'Avoid using octal, hexadecimal or binary',
                $lNum
            );
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Scalar\LNumber::class,
        ];
    }
}
