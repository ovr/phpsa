<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

/**
 * Manages use statements
 */
class AliasManager
{
    /**
     * Current namespace, but this can be null
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string[] a list of imported namespaces
     */
    protected $aliases = [];

    /**
     * @param string|null $namespace
     */
    public function __construct($namespace = null)
    {
        $this->namespace = $namespace;
    }

    /**
     * Checks whether a namespace was imported via use statement.
     *
     * @param string $classNS
     * @return bool
     */
    public function isClassImported($classNS)
    {
        if (in_array($classNS, $this->aliases)) {
            return true;
        }

        return false;
    }

    /**
     * Imports a namespace as an alias via use statement.
     *
     * @param string $namespace
     */
    public function add($namespace)
    {
        $this->aliases[] = $namespace;
    }

    /**
     * Gets the current namespace.
     *
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Sets the current namespace.
     *
     * @param null|string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
