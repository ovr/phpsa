<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Pass\ConfigurablePassInterface;
use PHPSA\Compiler\Event\StatementBeforeCompile;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Webiny\Component\EventManager\EventManager;

class OldConstructor implements ConfigurablePassInterface, AnalyzerPassInterface
{

    /**
     * @param Class_ $classStmt
     * @param Context $context
     * @return bool
     */
    public function pass(Class_ $classStmt, Context $context)
    {
        foreach ($classStmt->stmts as $statement) {
            if (!($statement instanceof ClassMethod) || $statement->name !== $classStmt->name) {
                continue;
            }

            $context->notice(
                'deprecated.constructor',
                sprintf('Class %s uses a PHP4 constructor.', $classStmt->name),
                $classStmt
            );

            return true;
        }

        return false;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('old_constructor')
            ->canBeDisabled()
        ;

        return $treeBuilder;
    }

    /**
     * @return array
     */
    public function register(EventManager $eventManager)
    {
        return [
            [Class_::class, StatementBeforeCompile::EVENT_NAME],
        ];
    }
}
