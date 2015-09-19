<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\CompiledExpression;
use PHPSA\Variable;

class Parameter extends Variable
{
    /**
     * @param string $name
     * @param null $defaultValue
     * @param int $type
     * @param bool|false $referenced
     */
    public function __construct($name, $defaultValue = null, $type = CompiledExpression::UNKNOWN, $referenced = false)
    {
        parent::__construct($name, $defaultValue, $type);

        $this->referenced = $referenced;
    }

    /**
     * @return string
     */
    public function getSymbolType()
    {
        return 'parameter';
    }
}
