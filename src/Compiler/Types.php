<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\CompiledExpression;
use RuntimeException;

class Types
{
    /**
     * Get type by $value
     *
     * @param $value
     * @return int
     */
    public static function getType($value)
    {
        $type = gettype($value);
        switch ($type) {
            case 'integer':
                return CompiledExpression::LNUMBER;
            case 'float':
            case 'double':
                return CompiledExpression::DNUMBER;
            case 'string':
                return CompiledExpression::STRING;
            case 'array':
                return CompiledExpression::ARR;
            case 'boolean':
                return CompiledExpression::BOOLEAN;
            case 'NULL':
                return CompiledExpression::NULL;
        }

        //@codeCoverageIgnoreStart
        throw new RuntimeException("Type '{$type}' is not supported");
        //@codeCoverageIgnoreEnd

    }
}
