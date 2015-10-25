su<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\CompiledExpression;
use RuntimeException;

class Types
{
    /**
     * Get name for the $type
     *
     * @param integer $type
     * @return string
     */
    public static function getTypeName($type)
    {
        switch ($type) {
            case CompiledExpression::INTEGER:
                return 'integer';
            case CompiledExpression::DOUBLE:
                return 'double';
            case CompiledExpression::NUMBER:
                return 'number';
            case CompiledExpression::ARR:
                return 'array';
            case CompiledExpression::OBJECT:
                return 'object';
            case CompiledExpression::RESOURCE:
                return 'resource';
            case CompiledExpression::CALLABLE_TYPE:
                return 'callable';
            case CompiledExpression::BOOLEAN:
                return 'boolean';
            case CompiledExpression::NULL:
                return 'null';
            default:
                return 'uknown';
        }
    }

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
