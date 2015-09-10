<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class Variable
{
    protected $name;

    protected $value;

    protected $gets = 0;

    protected $sets = 0;

    /**
     * @var bool
     */
    protected $referenced = false;

    /**
     * @todo soon
     */
    protected $type;

    public function __construct($name, $defaultValue = null, $type = CompiledExpression::UNKNOWN, $referenced = false)
    {
        $this->name = $name;

        if (!is_null($defaultValue)) {
            $this->sets++;
            $this->value = $defaultValue;
        }

        $this->type = $type;
        $this->referenced = $referenced;
    }

    /**
     * @return int
     */
    public function incGets()
    {
        return $this->gets++;
    }

    /**
     * @return int
     */
    public function incSets()
    {
        return $this->sets++;
    }

    /**
     * @return int
     */
    public function getGets()
    {
        return $this->gets;
    }

    /**
     * @return int
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    public function modifyType($type)
    {
        $this->type = $type;
    }

    public function modify($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function incUse()
    {
        $this->incGets();
        $this->incSets();
    }

    public function inc()
    {
        $this->value++;
    }

    public function dec()
    {
        $this->value--;
    }

    /**
     * @return boolean
     */
    public function isReferenced()
    {
        return $this->referenced;
    }
}
