<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 13.09.15
 * Time: 19:52
 */

namespace PHPSA;

use Exception;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\ClassMethod;
use PHPSA\Definition\FunctionDefinition;
use RuntimeException;
use PhpParser\Node;

/**
 * Class ParseTask
 * @package PHPSA
 *
 * @property ParserWorker $worker
 */
class ParseTask extends \Collectable
{
    private $filepath;

    private $compiler;

    public function __construct($filepath, Context $context)
    {
        $this->filepath = $filepath;
        $this->context = $context;
    }

    public function run()
    {
        include_once __DIR__ . '/../vendor/autoload.php';

        $filepath = $this->filepath;
        $context = $this->context;

        $parser = $this->worker->getParser();
        $parser->parse(file_get_contents($filepath));
        var_dump(spl_object_hash($this->worker->getCompiler()));

//        $this->compiler = $this->worker->getCompiler();

//        $astTraverser = new \PhpParser\NodeTraverser();
//        $astTraverser->addVisitor(new \PHPSA\Visitor\FunctionCall);
//        $astTraverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver());

        try {
            if (!is_readable($filepath)) {
                throw new RuntimeException('File ' . $filepath . ' is not readable');
            }

            var_dump('Precompile: ' . $filepath . '.');
//            $context->output->writeln('<comment>Precompile: ' . $filepath . '.</comment>');

            $code = file_get_contents($filepath);
            $astTree = $parser->parse($code);

//            $astTraverser->traverse($astTree);

            $aliasManager = new AliasManager();
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
                        $aliasManager->setNamespace($namespace);
                    }

                    if ($topStatement->stmts) {
                        $this->parseTopDefinitions($topStatement->stmts, $aliasManager, $filepath, $namespace);
                    }
                } else {
                    $this->parseTopDefinitions($topStatement, $aliasManager, $filepath, $namespace);
                }
            }

            $context->clear();
        } catch (\PhpParser\Error $e) {
            $context->sytaxError($e, $filepath);
        } catch (Exception $e) {
            $context->output->writeln("<error>{$e->getMessage()}</error>");
        }

        $this->setGarbage();
    }

    protected function parseTopDefinitions($topStatement, $aliasManager, $filepath, $namespace)
    {
        foreach ($topStatement as $statement) {
            if ($statement instanceof Node\Stmt\Use_) {
                if (!empty($statement->uses)) {
                    foreach ($statement->uses as $use) {
                        $aliasManager->add($use->name->parts);
                    }
                }
            } elseif ($statement instanceof Node\Stmt\Class_) {
                $definition = new ClassDefinition($statement->name, $statement->type);
                $definition->setFilepath($filepath);
                $definition->setNamespace($namespace);

                foreach ($statement->stmts as $stmt) {
                    if ($stmt instanceof Node\Stmt\ClassMethod) {
                        $method = new ClassMethod($stmt->name, $stmt, $stmt->type);

                        $definition->addMethod($method);
                    } elseif ($stmt instanceof Node\Stmt\Property) {
                        $definition->addProperty($stmt);
                    } elseif ($stmt instanceof Node\Stmt\ClassConst) {
                        $definition->addConst($stmt);
                    }
                }

                $this->worker->getCompiler()->addClass($definition);
            } elseif ($statement instanceof Node\Stmt\Function_) {
                $definition = new FunctionDefinition($statement->name, $statement);
                $definition->setFilepath($filepath);
                $definition->setNamespace($namespace);

                $this->worker->getCompiler()->addFunction($definition);
            }
        }
    }
}
