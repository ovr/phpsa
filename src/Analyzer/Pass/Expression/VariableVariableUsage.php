<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class VariableVariableUsage implements Pass\AnalyzerPassInterface, Pass\ConfigurablePassInterface
{
    /**
     * @param Expr\Assign $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Assign $expr, Context $context)
    {
        if ($expr->var->name instanceof Expr\Variable) {
            $context->notice(
                'variable.dynamic_assignment',
                'Dynamic assignment is greatly discouraged.',
                $expr
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
        return [
            Expr\Assign::class
        ];
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('variable.dynamic_assignment')
            ->canBeDisabled()
        ;

        return $treeBuilder;
    }
}
