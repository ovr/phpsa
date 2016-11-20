<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;
use PhpParser\Node\Stmt;
use PHPSA\Compiler\Event;

/**
 * Trait Definition
 */
class TraitDefinition extends ParentDefinition
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var Stmt\Trait_
     */
    protected $statement;

    /**
     * @var ClassMethod[]
     */
    protected $methods = [];

    /**
     * @param string $name
     * @param Stmt\Trait_ $statement
     */
    public function __construct($name, Stmt\Trait_ $statement)
    {
        $this->name = $name;
        $this->statement = $statement;
    }

    /**
     * Compile the definition
     *
     * @param Context $context
     * @return boolean
     */
    public function compile(Context $context)
    {
        $context->setFilepath($this->filepath);

        $context->getEventManager()->fire(
            Event\StatementBeforeCompile::EVENT_NAME,
            new Event\StatementBeforeCompile(
                $this->statement,
                $context
            )
        );

        return true;
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
     * @return bool
     */
    public function precompile()
    {
        foreach ($this->statement->stmts as $stmt) {
            if ($stmt instanceof Stmt\ClassMethod) {
                $this->addMethod(new ClassMethod($stmt->name, $stmt, $stmt->type));
            }
        }

        return true;
    }

    /**
     * @param ClassMethod $method
     */
    public function addMethod(ClassMethod $method)
    {
        $this->methods[] = $method;
    }

    /**
     * @return ClassMethod[]
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
