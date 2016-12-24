<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use Countable;
use PHPSA\Variable;

class SymbolTable implements Countable
{
    /**
     * @var Variable[]
     */
    protected $variables = [];

    /**
     * @param Variable $variable
     */
    public function add(Variable $variable)
    {
        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * @param string $name
     * @return Variable|null
     */
    public function get($name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        return null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->variables);
    }

    /**
     * Clear symbol table
     */
    public function clear()
    {
        $this->variables = [];
    }

    /**
     * @return Variable[]
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
