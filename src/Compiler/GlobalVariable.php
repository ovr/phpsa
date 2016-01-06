<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\CompiledExpression;
use PHPSA\Variable;

class GlobalVariable extends Variable
{
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @param int $type
     */
    public function __construct($name, $defaultValue = null, $type = CompiledExpression::UNKNOWN)
    {
        parent::__construct($name, $defaultValue, $type);
    }

    /**
     * @return string
     */
    public function getSymbolType()
    {
        return 'global-variable';
    }
}
