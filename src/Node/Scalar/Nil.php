<?php

namespace PHPSA\Node\Scalar;

class Nil extends \PhpParser\Node\Scalar
{
    /** @var null */
    public $value;

    /**
     * Constructs a boolean node.
     *
     * @param null $value
     * @param array $attributes Additional attributes
     */
    public function __construct($value = null, array $attributes = array())
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
