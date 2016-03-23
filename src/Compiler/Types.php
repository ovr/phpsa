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
    public static function getTypeByValue($value)
    {
        return self::getType(gettype($value));
    }


    /**
     * Get type (integer) by $type
     *
     * @param string $typeName
     * @return int
     */
    public static function getType($typeName)
    {
        switch ($typeName) {
            case 'integer':
            case 'int':
                return CompiledExpression::INTEGER;
            case 'double':
                return CompiledExpression::DOUBLE;
            case 'string':
                return CompiledExpression::STRING;
            case 'resource':
                return CompiledExpression::RESOURCE;
            case 'callable':
                return CompiledExpression::CALLABLE_TYPE;
            case 'object':
                return CompiledExpression::OBJECT;
            case 'array':
                return CompiledExpression::ARR;
            case 'boolean':
                return CompiledExpression::BOOLEAN;
            case 'NULL':
                return CompiledExpression::NULL;
        }

        //@codeCoverageIgnoreStart
        throw new RuntimeException("Type '{$typeName}' is not supported");
        //@codeCoverageIgnoreEnd
    }
}
