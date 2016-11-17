<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use Ovr\PHPReflection\Types;
use PHPSA\Compiler\Types as CompilerTypes;
use RuntimeException;

/**
 * The result of the compiler
 */
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

    /**
     * Integer type
     */
    const INTEGER = Types::INT_TYPE;

    /**
     * Double/Float type
     */
    const DOUBLE = Types::DOUBLE_TYPE;

    /**
     * Double/Float type
     */
    const NUMBER = Types::NUMBER;

    /**
     * String type
     */
    const STRING = Types::STRING_TYPE;

    /**
     * Boolean type
     * true or false
     */
    const BOOLEAN = Types::BOOLEAN_TYPE;

    /**
     * Array type
     */
    const ARR = Types::ARRAY_TYPE;

    /**
     * Object type
     */
    const OBJECT = Types::OBJECT_TYPE;

    /**
     * Resource handler type
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
     * NULL type
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
     * Returns the value of the expression.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks whether the expressions value equals the given value.
     *
     * @param integer $value
     * @return boolean
     */
    public function isEquals($value)
    {
        return $this->value == $value;
    }

    /**
     * Returns the type of the expression.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the type of the expression as a string.
     *
     * @return string
     */
    public function getTypeName()
    {
        return CompilerTypes::getTypeName($this->type);
    }

    /**
     * Creates a variable from the expression.
     *
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
     * Returns debug info.
     *
     * @return array
     */
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
    public function isTypeKnown()
    {
        return $this->type !== self::UNKNOWN;
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        return in_array($this->type, [self::STRING, self::BOOLEAN, self::DOUBLE, self::INTEGER, self::NUMBER], true);
    }

    /**
     * @return bool
     */
    public function isArray()
    {
        return $this->type === self::ARR;
    }

    /**
     * @return bool
     */
    public function isObject()
    {
        return $this->type == self::OBJECT;
    }

    /**
     * @return bool
     */
    public function isCallable()
    {
        return $this->type == self::CALLABLE_TYPE;
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->value !== null || $this->type === self::NULL;
    }
}
