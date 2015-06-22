<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class CompiledExpression
{
    const UNKNOWN = 0;

    const LNUMBER = 1;

    const DNUMBER = 2;

    const STRING = 3;

    const BOOLEAN = 3;

    protected $type = self::UNKNOWN;

    protected $value;

    public function __construct($type, $value = null)
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
        return $this->value === $value;
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