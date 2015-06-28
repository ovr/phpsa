<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class CompiledExpression
{
    /**
     * Unknown type
     */
    const UNKNOWN = 0;

    /**
     * Int
     */
    const LNUMBER = 1;

    /**
     * Float
     */
    const DNUMBER = 2;

    /**
     * String
     */
    const STRING = 3;

    /**
     * Boolean
     * true or false
     */
    const BOOLEAN = 4;

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
}
