<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Compiler\Types;

class Variable
{
    const BRANCH_ROOT = 0;

    const BRANCH_CONDITIONAL_TRUE = 1;

    const BRANCH_CONDITIONAL_FALSE = 2;

    const BRANCH_CONDITIONAL_EXTERNAL = 3;

    const BRANCH_UNKNOWN = 4;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var integer|string
     */
    protected $branch;

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
     * @param mixed $defaultValue
     * @param int $type
     * @param int|string $branch
     */
    public function __construct($name, $defaultValue = null, $type = CompiledExpression::UNKNOWN, $branch = self::BRANCH_ROOT)
    {
        $this->name = $name;

        if (!is_null($defaultValue)) {
            $this->sets++;
            $this->value = $defaultValue;
        }

        $this->type = (int) $type;
        $this->branch = $branch;
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
     * @return string
     */
    public function getTypeName()
    {
        return Types::getTypeName($this->type);
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

        if ($this->referencedTo) {
            $this->referencedTo->modify($type, $value);
        }
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
        return (
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
        return $this->gets == 0 && $this->sets > 0;
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
     * @return string
     */
    public function getSymbolType()
    {
        return 'variable';
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        if ($this->value) {
            $value = 'Exists!';
        } else {
            $value = 'Doest not exist';
        }

        switch (gettype($this->value)) {
            case 'integer':
            case 'double':
                $value = $this->value;
                break;
        }

        return [
            'name' => $this->name,
            'type' => $this->type,
            'value' => [
                'type' => gettype($this->value),
                'value' => $value
            ],
            'branch' => $this->branch,
            'symbol-type' => $this->getSymbolType()
        ];
    }
}
