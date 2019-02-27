<?php

namespace PHPSA\Node\Scalar;

/**
 * Fake scalar node for testing purposes
 */
class Fake extends \PhpParser\Node\Scalar
{
    /** @var mixed */
    public $value;

    /** @var mixed */
    public $type;

    /**
     * Constructs a fake node.
     *
     * @param mixed $value      Value of the Node
     * @param mixed $type      Type of the node
     * @param array $attributes Additional attributes
     */
    public function __construct($value, $type, array $attributes = array())
    {
        parent::__construct($attributes);

        $this->value = $value;
        $this->type = $type;
    }

    //@codeCoverageIgnoreStart
    /**
     * @return array
     */
    public function getSubNodeNames(): array
    {
        return ['value', 'type'];
    }
    //@codeCoverageIgnoreEnd

    /**
     * Gets the type of the node.
     *
     * @return string Type of the node
     */
    public function getType(): string
    {
        return 'Fake';
    }
}
