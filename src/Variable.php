<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Compiler\Types;

/**
 * A Variable
 */
class Variable
{
    const BRANCH_ROOT = 0;

    const BRANCH_CONDITIONAL_TRUE = 1;

    const BRANCH_CONDITIONAL_FALSE = 2;

    const BRANCH_CONDITIONAL_EXTERNAL = 3;

    const BRANCH_UNKNOWN = 4;

    /**
     * @var string variable name
     */
    protected $name;

    /**
     * @var mixed variable value
     */
    protected $value;

    /**
     * @var integer|string
     */
    protected $branch;

    /**
     * @var int how many times was read from the var
     */
    protected $gets = 0;

    /**
     * @var int how many times was written to the var
     */
    protected $sets = 0;

    /**
     * @var bool is it referenced to another var?
     */
    protected $referenced = false;

    /**
     * @var Variable|null to which variable referenced?
     */
    protected $referencedTo;

    /**
     * @var int variable type
     */
    protected $type;

    /**
     * Creates a variable.
     *
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
     * Increases the read counter.
     *
     * @return int
     */
    public function incGets()
    {
        return $this->gets++;
    }

    /**
     * Increases the write counter.
     *
     * @return int
     */
    public function incSets()
    {
        return $this->sets++;
    }

    /**
     * Gets the read counter.
     *
     * @return int
     */
    public function getGets()
    {
        return $this->gets;
    }

    /**
     * Gets the write counter.
     *
     * @return int
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @return string
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Changes variable type.
     *
     * @param int $type
     */
    public function modifyType($type)
    {
        $this->type = (int) $type;
    }

    /**
     * Changes variable type and value.
     *
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

    /**
     * Increment uses for gets and sets
     */
    public function incUse()
    {
        $this->incGets();
        $this->incSets();
    }

    /**
     * Increment value of the variable
     */
    public function inc()
    {
        $this->value++;
    }

    /**
     * Decrement value of the variable
     */
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
     * Is it an integer,double or number.
     *
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
     * Check if you are setting values to variable but didn't use it (means get)
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

    //@codeCoverageIgnoreStart
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
    //@codeCoverageIgnoreEnd
}
