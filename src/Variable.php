<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class Variable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var int
     */
    protected $gets = 0;

    /**
     * @var int
     */
    protected $sets = 0;

    /**
     * @var bool
     */
    protected $referenced = false;

    /**
     * @var Variable|null
     */
    protected $referencedTo;

    /**
     * @var int
     */
    protected $type;

    /**
     * @param string $name
     * @param mixed|null $defaultValue
     * @param int $type
     */
    public function __construct($name, $defaultValue = null, $type = CompiledExpression::UNKNOWN)
    {
        $this->name = $name;

        if (!is_null($defaultValue)) {
            $this->sets++;
            $this->value = $defaultValue;
        }

        $this->type = (int) $type;
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

    /**
     * @param int $type
     */
    public function modifyType($type)
    {
        $this->type = (int) $type;
    }

    /**
     * @param int $type
     * @param mixed $value
     */
    public function modify($type, $value)
    {
        $this->type = (int) $type;
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

    /**
     * @return bool
     */
    public function isNumeric()
    {
        return (bool) (
            $this->type & CompiledExpression::INTEGER ||
            $this->type & CompiledExpression::DOUBLE ||
            $this->type == CompiledExpression::NUMBER
        );
    }

    /**
     * Check if you are setting values to variable but didn't use it (mean get)
     *
     * @return bool
     */
    public function isUnused()
    {
        return $this->getGets() == 0 && $this->incSets();
    }

    /**
     * @return null|Variable
     */
    public function getReferencedTo()
    {
        return $this->referencedTo;
    }

    /**
     * @param null|Variable $referencedTo
     */
    public function setReferencedTo(Variable $referencedTo = null)
    {
        $this->referenced = true;
        $this->referencedTo = $referencedTo;
    }

    /**
     * @param boolean $referenced
     */
    public function setReferenced($referenced)
    {
        $this->referenced = $referenced;
    }
}
