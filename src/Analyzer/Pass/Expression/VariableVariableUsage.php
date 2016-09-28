<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Pass;
use PHPSA\Compiler\Event\ExpressionAfterCompile;
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
        if ($expr->var instanceof Expr\Variable && $expr->var->name instanceof Expr\Variable) {
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
            [Expr\Assign::class, ExpressionAfterCompile::EVENT_NAME]
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
