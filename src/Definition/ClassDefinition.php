<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Variable;

/**
 * Class ClassDefinition
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
    protected $methods = array();

    /**
     * Class properties
     *
     * @var Node\Stmt\Property[]
     */
    protected $properties = array();

    /**
     * Class constants
     *
     * @var Node\Stmt\Const_[]
     */
    protected $constants = array();

    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

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
    protected $interfaces = array();

    /**
     * @param string $name
     * @param integer $type
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @param ClassMethod $methodDefintion
     */
    public function addMethod(ClassMethod $methodDefintion)
    {
        $this->methods[$methodDefintion->getName()] = $methodDefintion;
    }

    /**
     * @param Node\Stmt\Property $property
     */
    public function addProperty(Node\Stmt\Property $property)
    {
        $this->properties[$property->props[0]->name] = $property;
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
     * @return Node\Stmt\Property
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
     * @param array $interface
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
}
