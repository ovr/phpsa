<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use Ovr\PHPReflection\Types;
use PHPSA\Compiler\Types as CompilerTypes;
use RuntimeException;

class CompiledExpression
{
    /**
     * Unknown type
     */
    const UNKNOWN = Types::UNKNOWN_TYPE;

    /**
     * It's not unknown, It's unimplemented
     */
    const UNIMPLEMENTED = -100;

    /**
     * Void type
     */
    const VOID = Types::VOID_TYPE;

    const INTEGER = Types::INT_TYPE;

    /**
     * Double/Float
     */
    const DOUBLE = Types::DOUBLE_TYPE;

    /**
     * Double/Float
     */
    const NUMBER = Types::NUMBER;

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
    const ARR = Types::ARRAY_TYPE;

    /**
     * Object
     */
    const OBJECT = Types::OBJECT_TYPE;

    /**
     * Resource handler
     */
    const RESOURCE = Types::RESOURCE_TYPE;

    /**
     * Callable type
     */
    const CALLABLE_TYPE = Types::CALLABLE_TYPE;

    /**
     * Value is handled in variable
     */
    const VARIABLE = 512;

    /**
     * Resource handler
     */
    const NULL = Types::NULL_TYPE;

    /**
     * self::INT_TYPE | self::DOUBLE_TYPE | self::STRING_TYPE | self::BOOLEAN_TYPE | self::ARRAY_TYPE | self::RESOURCE_TYPE | self::OBJECT_TYPE | self::NULL_TYPE
     */
    const MIXED = Types::MIXED;

    /**
     * I can't explain what it's :D
     */
    const DYNAMIC = 10000;

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
     * @var Variable|null
     */
    protected $variable;

    /**
     * Construct new CompiledExpression to pass result
     *
     * @param int $type
     * @param mixed $value
     * @param Variable|null $variable
     */
    public function __construct($type = self::UNKNOWN, $value = null, Variable $variable = null)
    {
        $this->type = $type;
        $this->value = $value;
        $this->variable = $variable;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param integer $value
     * @return boolean
     */
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
     * @return string
     */
    public function getTypeName()
    {
        return CompilerTypes::getTypeName($this->type);
    }

    /**
     * @param string $name Name of the Variable
     * @return Variable
     */
    public function toVariable($name)
    {
        return new Variable($name, $this->value, $this->type);
    }

    /**
     * If we don't know $type but know $value
     *
     * @param $value
     * @throws RuntimeException
     * @return CompiledExpression
     */
    public static function fromZvalValue($value)
    {
        return new CompiledExpression(CompilerTypes::getTypeByValue($value), $value);
    }

    /**
     * This is needed via in feature $this->type can store multiple type(s) by bitmask
     *
     * @return bool
     */
    public function canBeObject()
    {
        return (boolean) ($this->type == self::OBJECT || $this->type & self::OBJECT);
    }

    /**
     * @return bool
     */
    public function isString()
    {
        return $this->type == self::STRING;
    }

    /**
     * @return bool
     */
    public function isObject()
    {
        return $this->type == self::OBJECT;
    }

    public function __debugInfo()
    {
        return [
            'type' => \PHPSA\Compiler\Types::getTypeName($this->type),
            'value' => $this->value,
        ];
    }

    //@codeCoverageIgnoreStart
    /**
     * Check that $this->value is correct for $this->type
     *
     * @todo Implement it ;)
     * @return boolean
     */
    public function isCorrectValue()
    {
        $type = gettype($this->value);

        switch ($this->type) {
            case CompiledExpression::INTEGER:
                return $type == 'integer';
            case CompiledExpression::NUMBER:
                return $type == 'integer' || $type == 'double';
            case CompiledExpression::DOUBLE:
                return $type == 'double';
            case CompiledExpression::BOOLEAN:
                return $type == 'boolean';
            case CompiledExpression::ARR:
                return $type == 'array';
        }

        return true;
    }
    //@codeCoverageIgnoreEnd

    /**
     * @return Variable|null
     */
    public function getVariable()
    {
        return $this->variable;
    }
}
