<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;
use PHPSA\Analyzer\Pass\Metadata;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class MissingDocblock implements AnalyzerPassInterface
{
    const DESCRIPTION = 'Checks for a missing docblock for: class, property, class constant, trait, interface, class method, function.';

    /**
     * Contains the Nodes that are registered
     */
    public $register = [];

    /**
     * @param array $config The config values for the analyzer
     */
    public function __construct(array $config)
    {
        if ($config["class"] == true) {
            $this->register[] = Stmt\Class_::class;
        }
        if ($config["class_method"] == true) {
            $this->register[] = Stmt\ClassMethod::class;
        }
        if ($config["class_const"] == true) {
            $this->register[] = Stmt\ClassConst::class;
        }
        if ($config["class_property"] == true) {
            $this->register[] = Stmt\Property::class;
        }
        if ($config["function"] == true) {
            $this->register[] = Stmt\Function_::class;
        }
        if ($config["interface"] == true) {
            $this->register[] = Stmt\Interface_::class;
        }
        if ($config["trait"] == true) {
            $this->register[] = Stmt\Trait_::class;
        }
    }

    /**
     * @param Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt $stmt, Context $context)
    {
        if ($stmt->getDocComment() === null) {
            $context->notice(
                'missing_docblock',
                'Missing Docblock',
                $stmt
            );

            return true;
        }
        
        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * @return Metadata
     */
    public static function getMetadata()
    {
        $treebuilder = new TreeBuilder();
        $config = $treebuilder->root("missing_docblock")
            ->info(self::DESCRIPTION)
            ->canBeDisabled()
            ->children()
                ->booleanNode("class")->defaultTrue()->end()
                ->booleanNode("class_method")->defaultTrue()->end()
                ->booleanNode("class_const")->defaultTrue()->end()
                ->booleanNode("class_property")->defaultTrue()->end()
                ->booleanNode("function")->defaultTrue()->end()
                ->booleanNode("interface")->defaultTrue()->end()
                ->booleanNode("trait")->defaultTrue()->end()
            ->end();

        return new Metadata("missing_docblock", $config, self::DESCRIPTION);
    }
}
