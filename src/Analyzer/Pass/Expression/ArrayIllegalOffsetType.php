<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ArrayIllegalOffsetType implements Pass\AnalyzerPassInterface, Pass\ConfigurablePassInterface
{
    /**
     * @param Expr\Array_|Expr\Assign $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        if ($expr instanceof Expr\Array_) {
            return $this->analyzeArray($expr, $context);
        } else if ($expr instanceof Expr\Assign && $expr->var instanceof Expr\ArrayDimFetch) {
            return $this->analyzeDimensionFetch($expr->var, $context);
        }
    }

    /**
     * @param Expr\ArrayDimFetch $expr
     * @param Context $context
     *
     * @return bool
     */
    private function analyzeDimensionFetch(Expr\ArrayDimFetch $expr, Context $context)
    {
        return $this->analyzeExpression($expr->dim, $context);
    }

    /**
     * @param Expr\Array_ $expr
     * @param Context $context
     *
     * @return bool
     */
    private function analyzeArray(Expr\Array_ $expr, Context $context)
    {
        $result = false;

        /** @var Expr\ArrayItem $item */
        foreach ($expr->items as $item) {
            if ($item->key === null) {
                continue;
            }

            $result = $this->analyzeExpression($item->key, $context) || $result;
        }

        return $result;
    }

    private function analyzeExpression(Expr $expr, Context $context)
    {
        $compiledKey = $context->getExpressionCompiler()->compile($expr);
        if (!$compiledKey->isTypeKnown() || $compiledKey->isScalar()) {
            return false;
        }

        $keyType = $compiledKey->getTypeName();
        if ($compiledKey->isObject() && $compiledKey->getValue()) {
            $keyType = get_class($compiledKey->getValue());
        }

        if ($expr instanceof Expr\Variable) {
            $message = sprintf('Illegal array offset type %s for key %s.', $keyType, '$'.$expr->name);
        } else {
            $message = sprintf('Illegal array offset type %s.', $keyType);
        }

        $context->notice('array.illegal_offset_type', $message, $expr);

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Expr\Array_::class,
            Expr\Assign::class,
        ];
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('array.illegal_offset_type')
            ->canBeDisabled()
        ;

        return $treeBuilder;
    }
}
