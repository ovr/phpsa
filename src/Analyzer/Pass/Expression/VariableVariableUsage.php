<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
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
        // $(this->)something[] = …
        if ($expr->var instanceof Expr\ArrayDimFetch) {
            return $this->analyzeArrayDimFetch($expr->var, $context);
        }

        // $this->something = …
        if ($expr->var instanceof Expr\PropertyFetch) {
            return $this->analyzePropertyFetch($expr->var, $context);
        }

        // $something = …
        return $this->analyzeAssign($expr, $context);
    }

    private function analyzeAssign(Expr\Assign $expr, Context $context)
    {
        // list($a, $b) = …
        if ($expr->var instanceof Expr\List_) {
            return $this->analyzeList($expr->var, $context);
        }

        return $this->analyzeVar($expr->var, $context);
    }

    private function analyzeList(Expr\List_ $expr, Context $context)
    {
        $result = false;

        foreach ($expr->vars as $var) {
            $result = $this->analyzeVar($var, $context) || $result;
        }

        return $result;
    }

    private function analyzeArrayDimFetch(Expr\ArrayDimFetch $expr, Context $context)
    {
        $result = false;

        // $array[] = …
        if ($expr->var instanceof Expr\Variable) {
            $result = $this->analyzeVar($expr->var, $context);
        } else if ($expr->var instanceof Expr\PropertyFetch) {
            // $this->array[] = …
            $result = $this->analyzePropertyFetch($expr->var, $context);
        }

        if ($expr->dim instanceof Expr\Variable) {
            $result = $this->analyzeVar($expr->dim, $context) || $result;
        }

        return $result;
    }

    private function analyzePropertyFetch(Expr\PropertyFetch $expr, Context $context)
    {
        if ($expr->name instanceof Expr\Variable) {
            $this->notice($context, $expr->name);
            return true;
        }

        return false;
    }

    private function analyzeVar(Expr\Variable $var, Context $context)
    {
        if (!$var->name instanceof Expr\Variable) {
            return false;
        }

        $this->notice($context, $var);

        return true;
    }

    private function notice(Context $context, Expr $expr)
    {
        $context->notice('variable.dynamic_assignment', 'Dynamic assignment is greatly discouraged.', $expr);
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
