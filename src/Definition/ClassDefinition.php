<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Variable;
use PHPSA\Compiler\Event;

/**
 * Class Definition
 *
 * @package PHPSA\Definition
 */
class ClassDefinition extends ParentDefinition
{
    /**
     * @var int
     */
    protected $type;

    /**
     * Class methods
     *
     * @var ClassMethod[]
     */
    protected $methods = [];

    /**
     * Class properties
     *
     * @var Node\Stmt\PropertyProperty[]
     */
    protected $properties = [];

    /**
     * Property Statements
     *
     * @var Node\Stmt\Property[]
     */
    protected $propertyStatements = [];

    /**
     * Class constants
     *
     * @var Node\Stmt\Const_[]
     */
    protected $constants = [];

    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

    /**
     * @var Node\Stmt\Class_|null
     */
    protected $statement;

    /**
     * @var string|null
     */
    protected $extendsClass;

    /**
     * @var ClassDefinition|null
     */
    protected $extendsClassDefinition;

    /**
     * @var array
     */
    protected $interfaces = [];

    /**
     * @param string $name
     * @param Node\Stmt\Class_ $statement
     * @param integer $type
     */
    public function __construct($name, Node\Stmt\Class_ $statement = null, $type = 0)
    {
        $this->name = $name;
        $this->statement = $statement;
        $this->type = $type;
    }

    /**
     * @param ClassMethod $classMethod
     * @param bool $overwrite Should we overwrite method if it already exists
     * @return bool Did we overwrite method?
     */
    public function addMethod(ClassMethod $classMethod, $overwrite = true)
    {
        if ($overwrite) {
            $this->methods[$classMethod->getName()] = $classMethod;
        } else {
            $name = $classMethod->getName();
            if (isset($this->methods[$name])) {
                return false;
            } else {
                $this->methods[$name] = $classMethod;
            }
        }

        return true;
    }

    /**
     * @param Node\Stmt\Property $property
     */
    public function addProperty(Node\Stmt\Property $property)
    {
        foreach ($property->props as $propertyDefinition) {
            $this->properties[$propertyDefinition->name] = $propertyDefinition;
        }

        $this->propertyStatements[$propertyDefinition->name] = $property;
    }

    /**
     * @param Node\Stmt\ClassConst $const
     */
    public function addConst(Node\Stmt\ClassConst $const)
    {
        $this->constants[$const->consts[0]->name] = $const;
    }

    /**
     * @param Context $context
     * @return $this
     */
    public function compile(Context $context)
    {
        if ($this->compiled) {
            return true;
        }

        $this->compiled = true;
        $context->setFilepath($this->filepath);
        $context->setScope($this);

        $context->getEventManager()->fire(
            Event\StatementBeforeCompile::EVENT_NAME,
            new Event\StatementBeforeCompile(
                $this->statement,
                $context
            )
        );

        // Compile event for properties
        foreach ($this->properties as $property) {
            if (!$property->default) {
                continue;
            }

            // fire expression event for property default
            $context->getEventManager()->fire(
                Event\ExpressionBeforeCompile::EVENT_NAME,
                new Event\ExpressionBeforeCompile(
                    $property->default,
                    $context
                )
            );
        }

        // Compile event for PropertyProperty
        foreach ($this->properties as $property) {
            $context->getEventManager()->fire(
                Event\StatementBeforeCompile::EVENT_NAME,
                new Event\StatementBeforeCompile(
                    $property,
                    $context
                )
            );
        }

        // Compile event for constants
        foreach ($this->constants as $const) {
            $context->getEventManager()->fire(
                Event\StatementBeforeCompile::EVENT_NAME,
                new Event\StatementBeforeCompile(
                    $const,
                    $context
                )
            );
        }

        // Compiler event for property statements
        foreach ($this->propertyStatements as $prop) {
            $context->getEventManager()->fire(
                Event\StatementBeforeCompile::EVENT_NAME,
                new Event\StatementBeforeCompile(
                    $prop,
                    $context
                )
            );
        }

        // Compile each method
        foreach ($this->methods as $method) {
            $context->clearSymbols();

            if (!$method->isStatic()) {
                $thisPtr = new Variable('this', $this, CompiledExpression::OBJECT);
                $thisPtr->incGets();
                $context->addVariable($thisPtr);
            }

            $method->compile($context);

            $symbols = $context->getSymbols();
            if (count($symbols) > 0) {
                foreach ($symbols as $name => $variable) {
                    if ($variable->isUnused()) {
                        $context->warning(
                            'unused-' . $variable->getSymbolType(),
                            sprintf(
                                'Unused ' . $variable->getSymbolType() . ' $%s in method %s()',
                                $variable->getName(),
                                $method->getName()
                            )
                        );
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param boolean|false $inherit
     * @return bool
     */
    public function hasMethod($name, $inherit = false)
    {
        if (isset($this->methods[$name])) {
            return true;
        }

        if ($inherit && $this->extendsClassDefinition && $this->extendsClassDefinition->hasMethod($name, $inherit)) {
            $method = $this->extendsClassDefinition->getMethod($name, $inherit);
            return $method && ($method->isPublic() || $method->isProtected());
        }

        return false;
    }

    /**
     * @param string $name
     * @param bool $inherit
     * @return bool
     */
    public function hasConst($name, $inherit = false)
    {
        if ($inherit && $this->extendsClassDefinition && $this->extendsClassDefinition->hasConst($name, $inherit)) {
            return true;
        }

        return isset($this->constants[$name]);
    }

    /**
     * @param $name
     * @param boolean|false $inherit
     * @return ClassMethod|null
     */
    public function getMethod($name, $inherit = false)
    {
        if (isset($this->methods[$name])) {
            return $this->methods[$name];
        }

        if ($inherit && $this->extendsClassDefinition) {
            return $this->extendsClassDefinition->getMethod($name, $inherit);
        }

        return null;
    }

    /**
     * @param $name
     * @param bool $inherit
     * @return bool
     */
    public function hasProperty($name, $inherit = false)
    {
        if (isset($this->properties[$name])) {
            return isset($this->properties[$name]);
        }

        return $inherit && $this->extendsClassDefinition && $this->extendsClassDefinition->hasProperty($name, true);
    }

    /**
     * @param string $name
     * @param bool $inherit
     * @return Node\Stmt\PropertyProperty
     */
    public function getProperty($name, $inherit = false)
    {
        assert($this->hasProperty($name, $inherit));

        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        if ($inherit && $this->extendsClassDefinition) {
            return $this->extendsClassDefinition->getProperty($name, true);
        }

        return null;
    }

    /**
     * @param string $name
     * @param bool $inherit
     * @return Node\Stmt\Property
     */
    public function getPropertyStatement($name, $inherit = false)
    {
        if (isset($this->propertyStatements[$name])) {
            return $this->propertyStatements[$name];
        }

        if ($inherit && $this->extendsClassDefinition) {
            return $this->extendsClassDefinition->getPropertyStatement($name, true);
        }

        return null;
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
    public function isAbstract()
    {
        return (bool) ($this->type & Node\Stmt\Class_::MODIFIER_ABSTRACT);
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return (bool) ($this->type & Node\Stmt\Class_::MODIFIER_FINAL);
    }

    /**
     * @param null|string $extendsClass
     */
    public function setExtendsClass($extendsClass)
    {
        $this->extendsClass = $extendsClass;
    }

    /**
     * @return null|ClassDefinition
     */
    public function getExtendsClassDefinition()
    {
        return $this->extendsClassDefinition;
    }

    /**
     * @param ClassDefinition $extendsClassDefinition
     */
    public function setExtendsClassDefinition(ClassDefinition $extendsClassDefinition)
    {
        $this->extendsClassDefinition = $extendsClassDefinition;
    }

    /**
     * @param string $interface
     */
    public function addInterface($interface)
    {
        $this->interfaces[] = $interface;
    }

    /**
     * @return null|string
     */
    public function getExtendsClass()
    {
        return $this->extendsClass;
    }

    /**
     * @param TraitDefinition $definition
     * @param Node\Stmt\TraitUseAdaptation\Alias[] $adaptations
     */
    public function mergeTrait(TraitDefinition $definition, array $adaptations)
    {
        $methods = $definition->getMethods();
        if ($methods) {
            foreach ($adaptations as $adaptation) {
                // We don't support Trait name for now
                if (!$adaptation->trait) {
                    $methodNameFromTrait = $adaptation->method;
                    if (isset($methods[$methodNameFromTrait])) {
                        /** @var ClassMethod $method Method from Trait */
                        $method = $methods[$methodNameFromTrait];
                        if ($adaptation->newName
                            || ($adaptation->newModifier && $method->getModifier() != $adaptation->newModifier)) {
                            // Don't modify original method from Trait
                            $method = clone $method;
                            $method->setName($adaptation->newName);
                            $method->setModifier($adaptation->newModifier);

                            $methods[$methodNameFromTrait] = $method;
                        }
                    }
                }
            }

            foreach ($methods as $method) {
                $this->addMethod($method, false);
            }
        }
    }
}
