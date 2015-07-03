<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use Ovr\PHPReflection\Types;
use RuntimeException;

class CompiledExpression
{
    /**
     * Unknown type
     */
    const UNKNOWN = 0;

    const INTEGER = Types::INT_TYPE;

    /**
     * @deprectated
     */
    const LNUMBER = self::INTEGER;

    /**
     * Double/Float
     */
    const DOUBLE = Types::DOUBLE_TYPE;

    /**
     * Double/Float
     * @deprectated
     */
    const DNUMBER = self::DOUBLE;

    /**
     * String
     */
    const STRING = Types::STRING_TYPE;

    /**
     * Boolean
     * true or false
     */
    const BOOLEAN = Types::BOOLEAN_TYPE;

    /**
     * Array
     */
    const ARR = 5;

    /**
     * Object
     */
    const OBJECT = 6;

    /**
     * Resource handler
     */
    const RESOURCE = 7;

    /**
     * Resource handler
     */
    const NULL = 8;


    /**
     * We cant explain what it's
     */
    const DYNAMIC = 9;

    /**
     * By default we don't know what it is
     *
     * @var int
     */
    protected $type;

    /**
     * Possible value
     *
     * @var mixed
     */
    protected $value;


    /**
     * Construct new CompiledExpression to pass result
     *
     * @param int $type
     * @param null $value
     */
    public function __construct($type = self::UNKNOWN, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function isEquals($value)
    {
        return $this->value == $value;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Variable
     */
    public function toVariable()
    {
        return new Variable($this->type, $this->value);
    }

    /**
     * If we don't know $type but know $value
     *
     * @param $value
     * @return CompiledExpression|RuntimeException
     */
    public static function fromZvalValue($value)
    {
        $type = gettype($value);
        switch ($type) {
            case 'integer':
                return new CompiledExpression(self::LNUMBER, $value);
                break;
            case 'double':
                return new CompiledExpression(self::DNUMBER, $value);
                break;
            case 'boolean':
                return new CompiledExpression(self::BOOLEAN, $value);
                break;
        }

        return new RuntimeException("Type '{$type}' is not supported");
    }
}
