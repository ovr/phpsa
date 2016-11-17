<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class VariableVariableUsage implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Discourages the use of variable variables.';

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

    /**
     * @param Expr\Assign $expr
     * @param Context $context
     * @return bool
     */
    private function analyzeAssign(Expr\Assign $expr, Context $context)
    {
        // list($a, $b) = …
        if ($expr->var instanceof Expr\List_) {
            return $this->analyzeList($expr->var, $context);
        }

        // $a = ... or class::$a =
        return $this->analyzeVar($expr->var, $context);
    }

    /**
     * @param Expr\List_ $expr
     * @param Context $context
     * @return bool
     */
    private function analyzeList(Expr\List_ $expr, Context $context)
    {
        $result = false;

        foreach ($expr->vars as $var) {
            // list($a, ) = …
            if (!$var) {
                continue;
            }

            $result = $this->analyzeVar($var, $context) || $result;
        }

        return $result;
    }

    /**
     * @param Expr\ArrayDimFetch $expr
     * @param Context $context
     * @return bool
     */
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

    /**
     * @param Expr\PropertyFetch $expr
     * @param Context $context
     * @return bool
     */
    private function analyzePropertyFetch(Expr\PropertyFetch $expr, Context $context)
    {
        if ($expr->name instanceof Expr\Variable) {
            $this->notice($context, $expr->name);
            return true;
        }

        return false;
    }


    /**
     * @param Expr\Variable|Expr\StaticPropertyFetch $var
     * @param Context $context
     */
    private function analyzeVar(Expr $var, Context $context)
    {
        if (!$var->name instanceof Expr\Variable) {
            return false;
        }

        $this->notice($context, $var);

        return true;
    }

    /**
     * @param Context $context
     * @param Expr $expr
     */
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
}
