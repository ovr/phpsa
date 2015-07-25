<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class AliasManager
{
    /**
     * Current namespace, but this can be null
     * @var string|null
     */
    protected $namespace = null;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @var string[]
     */
    protected $aliases = array();

    public function isClassImported($classNS)
    {
        if (isset($this->aliases[$classNS])) {
            return true;
        }

        return false;
    }

    public function add($ns)
    {
        $this->aliases[] = $ns;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
