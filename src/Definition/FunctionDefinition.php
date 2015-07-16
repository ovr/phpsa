<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;
use PhpParser\Node;

/**
 * Class FunctionDefinition
 * @package PHPSA\Definition
 */
class FunctionDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Compile methods to check it
     *
     * @param Context $context
     */
    public function compile(Context $context)
    {
        $context->setScope(null);
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
