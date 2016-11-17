<?php
/**
 * @author Leonardo da Mata https://github.com/barroca <barroca@gmail.com>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Property;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class HasMoreThanOneProperty implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for multiple property definitions in one line. For example public $a, $b; and discourages it.';

    /**
     * @param Property $prop
     * @param Context $context
     * @return bool
     */
    public function pass(Property $prop, Context $context)
    {
        if (count($prop->props) > 1) {
            $context->notice('limit.properties', 'Number of properties larger than one.', $prop);
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
            Property::class,
        ];
    }
}
