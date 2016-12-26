<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use Exception;
use PHPSA\AliasManager;
use PHPSA\Compiler;
use PHPSA\Context;
use PhpParser\Node;
use RuntimeException;

class FileParser
{
    /**
     * @var \PhpParser\Parser
     */
    protected $parser;

    /**
     * @var \PhpParser\NodeTraverser
     */
    protected $nodeTraverser;

    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @param \PhpParser\Parser $parser
     * @param Compiler $compiler
     */
    public function __construct(\PhpParser\Parser $parser, Compiler $compiler)
    {
        $this->nodeTraverser = new \PhpParser\NodeTraverser();
        $this->nodeTraverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver);

        $this->parser = $parser;
        $this->compiler = $compiler;
    }

    /**
     * @param string $filepath
     * @param Context $context
     * @throws RuntimeException when filepath is not readable
     */
    public function parserFile($filepath, Context $context)
    {
        $context->setFilepath($filepath);

        try {
            if (!is_readable($filepath)) {
                throw new RuntimeException('File ' . $filepath . ' is not readable');
            }

            $context->debug('<comment>Precompile: ' . $filepath . '.</comment>');

            $code = file_get_contents($filepath);
            $astTree = $this->parser->parse($code);

            $this->nodeTraverser->traverse($astTree);

            $context->aliasManager = new AliasManager();
            $namespace = null;

            /**
             * Step 1 Precompile
             */
            foreach ($astTree as $topStatement) {
                if ($topStatement instanceof Node\Stmt\Namespace_) {
                    /**
                     * Namespace block can be created without NS name
                     */
                    if ($topStatement->name) {
                        $namespace = $topStatement->name->toString();
                        $context->aliasManager->setNamespace($namespace);
                    }

                    if ($topStatement->stmts) {
                        $this->parseTopDefinitions($topStatement->stmts, $context->aliasManager, $filepath);
                    }
                } else {
                    if (is_array($topStatement)) {
                        $this->parseTopDefinitions($topStatement, $context->aliasManager, $filepath);
                    } else {
                        $this->parseTopDefinitions($astTree, $context->aliasManager, $filepath);
                    }
                }
            }

            $context->clear();
        } catch (\PhpParser\Error $e) {
            $context->syntaxError($e, $filepath);
        } catch (Exception $e) {
            $context->output->writeln("<error>{$e->getMessage()}</error>");
        }
    }

    /**
     * @param Node\Stmt|Node\Stmt[] $topStatement
     * @param AliasManager $aliasManager
     * @param string $filepath
     */
    protected function parseTopDefinitions($topStatement, AliasManager $aliasManager, $filepath)
    {
        foreach ($topStatement as $statement) {
            if ($statement instanceof Node\Stmt\Use_) {
                if (count($statement->uses) > 0) {
                    foreach ($statement->uses as $use) {
                        $aliasManager->add((string)$use->name);
                    }
                }
            } elseif ($statement instanceof Node\Stmt\GroupUse) {
                if (count($statement->uses) > 0) {
                    foreach ($statement->uses as $use) {
                        $aliasManager->add($statement->prefix->toString() . '\\' . $use->name->toString());
                    }
                }
            } elseif ($statement instanceof Node\Stmt\Trait_) {
                $definition = new TraitDefinition($statement->name, $statement);
                $definition->setFilepath($filepath);
                $definition->setNamespace($aliasManager->getNamespace());
                $definition->precompile();

                $this->compiler->addTrait($definition);
            } elseif ($statement instanceof Node\Stmt\Class_) {
                $definition = new ClassDefinition($statement->name, $statement, $statement->flags);
                $definition->setFilepath($filepath);
                $definition->setNamespace($aliasManager->getNamespace());

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
            } elseif ($statement instanceof Node\Stmt\Function_) {
                $definition = new FunctionDefinition($statement->name, $statement);
                $definition->setFilepath($filepath);
                $definition->setNamespace($aliasManager->getNamespace());

                $this->compiler->addFunction($definition);
            }
        }
    }
}
