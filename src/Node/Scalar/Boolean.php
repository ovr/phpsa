<?php

namespace PHPSA\Node\Scalar;

class Boolean extends \PhpParser\Node\Scalar
{
    /** @var boolean Number value */
    public $value;

    /**
     * Constructs a boolean node.
     *
     * @param boolean $value      Value of the number
     * @param array $attributes Additional attributes
     */
    public function __construct($value, array $attributes = array())
    {
        parent::__construct($attributes);
        $this->value = $value;
    }

    //@codeCoverageIgnoreStart
    public function getSubNodeNames()
    {
        return array('value');
    }
    //@codeCoverageIgnoreEnd
}
