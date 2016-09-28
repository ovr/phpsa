<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Pass\ConfigurablePassInterface;
use PHPSA\Compiler\Event\StatementBeforeCompile;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use phpDocumentor\Reflection\DocBlock;

class TestAnnotation implements ConfigurablePassInterface, AnalyzerPassInterface
{
    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        $functionName = $methodStmt->name;
        if (!$functionName) {
            return false;
        }

        if (substr($functionName, 0, 4) !== 'test') {
            return false;
        }

        if ($methodStmt->getDocComment()) {
            $phpdoc = new DocBlock($methodStmt->getDocComment()->getText());

            if ($phpdoc->hasTag('test')) {
                $context->notice(
                    'test.annotation',
                    'Annotation @test is not needed when the method is prefixed with test.',
                    $methodStmt
                );
                return true;
            }
        }
        return false;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('test_annotation')
            ->canBeDisabled()
        ;

        return $treeBuilder;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            [ClassMethod::class, StatementBeforeCompile::EVENT_NAME],
        ];
    }
}
