<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt;
use PHPSA\Compiler;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\ClassMethod;
use PHPSA\Definition\FunctionDefinition;
use PHPSA\Definition\TraitDefinition;

class DefinitionVisitor extends NodeVisitorAbstract
{
    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @var string|null
     */
    protected $filepath;

    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Stmt\Class_) {
            $this->prepareClass($node);
        } elseif ($node instanceof Stmt\Function_) {
            $this->prepareFunction($node);
        } elseif ($node instanceof Stmt\Trait_) {
            $this->prepareTrait($node);
        }
    }

    /**
     * @param Stmt\Trait_ $statement
     */
    public function prepareTrait(Stmt\Trait_ $statement)
    {
        $definition = new TraitDefinition($statement->name, $statement);
        $definition->setFilepath($this->filepath);

        if (isset($statement->namespacedName)) {
            /** @var \PhpParser\Node\Name $namespacedName */
            $namespacedName = $statement->namespacedName;
            $definition->setNamespace($namespacedName->toString());
        }

        $definition->precompile();
        $this->compiler->addTrait($definition);
    }

    /**
     * @param Stmt\Function_ $statement
     */
    public function prepareFunction(Stmt\Function_ $statement)
    {
        $definition = new FunctionDefinition($statement->name, $statement);
        $definition->setFilepath($this->filepath);

        if (isset($statement->namespacedName)) {
            /** @var \PhpParser\Node\Name $namespacedName */
            $namespacedName = $statement->namespacedName;
            $definition->setNamespace($namespacedName->toString());
        }

        $this->compiler->addFunction($definition);
    }

    /**
     * @param Stmt\Class_ $statement
     */
    public function prepareClass(Stmt\Class_ $statement)
    {
        $definition = new ClassDefinition($statement->name, $statement, $statement->flags);
        $definition->setFilepath($this->filepath);

        if (isset($statement->namespacedName)) {
            /** @var \PhpParser\Node\Name $namespacedName */
            $namespacedName = $statement->namespacedName;
            $definition->setNamespace($namespacedName->toString());
        }

        if ($statement->extends) {
            $definition->setExtendsClass($statement->extends->toString());
        }

        if ($statement->implements) {
            foreach ($statement->implements as $interface) {
                $definition->addInterface($interface->toString());
            }
        }

        foreach ($statement->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\ClassMethod) {
                $definition->addMethod(
                    new ClassMethod($stmt->name, $stmt, $stmt->flags)
                );
            } elseif ($stmt instanceof Node\Stmt\Property) {
                $definition->addProperty($stmt);
            } elseif ($stmt instanceof Node\Stmt\TraitUse) {
                foreach ($stmt->traits as $traitPart) {
                    $traitDefinition = $this->compiler->getTrait($traitPart->toString());
                    if ($traitDefinition) {
                        $definition->mergeTrait($traitDefinition, $stmt->adaptations);
                    }
                }
            } elseif ($stmt instanceof Node\Stmt\ClassConst) {
                $definition->addConst($stmt);
            }
        }

        $this->compiler->addClass($definition);
    }

    /**
     * @param string $filepath
     */
    public function setFilePath($filepath)
    {
        $this->filepath = $filepath;
    }
}
