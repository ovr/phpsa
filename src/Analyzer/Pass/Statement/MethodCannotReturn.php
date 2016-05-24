<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Pass\ConfigurablePassInterface;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class MethodCannotReturn implements ConfigurablePassInterface, AnalyzerPassInterface
{
    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        if (count($methodStmt->stmts) == 0) {
            return false;
        }

        $result = false;

        if ($methodStmt->name == '__construct' || $methodStmt->name == '__destruct') {
            foreach ($methodStmt->stmts as $stmt) {
                if ($stmt instanceof Return_) {
                    if (!$stmt->expr) {
                        continue;
                    }

                    $context->notice(
                        'return.construct',
                        sprintf('Method %s cannot return a value.', $methodStmt->name),
                        $stmt
                    );

                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('method_cannot_return')
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
            \PhpParser\Node\Stmt\ClassMethod::class
        ];
    }
}
