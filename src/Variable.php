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
     * @todo soon
     */
    protected $type;

    public function __construct($name, $defaultValue = null)
    {
        $this->name = $name;

        if (!is_null($defaultValue)) {
            $this->sets++;
            $this->value = $defaultValue;
        }
    }

    public function incGets()
    {
        $this->gets++;
    }

    public function incSets()
    {
        $this->sets++;
    }
}