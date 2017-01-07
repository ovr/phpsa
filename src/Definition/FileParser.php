<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Compiler;
use PHPSA\Context;
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
     * @var \PHPSA\Compiler\DefinitionVisitor
     */
    protected $definitionVisitor;

    /**
     * @param \PhpParser\Parser $parser
     * @param Compiler $compiler
     */
    public function __construct(\PhpParser\Parser $parser, Compiler $compiler)
    {
        $this->nodeTraverser = new \PhpParser\NodeTraverser();
        $this->nodeTraverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver);
        $this->nodeTraverser->addVisitor(
            $this->definitionVisitor = new \PHPSA\Compiler\DefinitionVisitor($compiler)
        );

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

            $this->definitionVisitor->setFilePath($filepath);
            $this->nodeTraverser->traverse($astTree);

            $context->clear();
        } catch (\PhpParser\Error $e) {
            $context->syntaxError($e, $filepath);
        } catch (\Exception $e) {
            $context->output->writeln("<error>{$e->getMessage()}</error>");
        }
    }
}
