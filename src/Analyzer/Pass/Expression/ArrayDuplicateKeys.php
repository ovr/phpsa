<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ArrayDuplicateKeys implements Pass\AnalyzerPassInterface, Pass\ConfigurablePassInterface
{
    /**
     * @param Expr\Array_ $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Array_ $expr, Context $context)
    {
        $result = false;
        $keys = [];

        /** @var Expr\ArrayItem $item */
        foreach ($expr->items as $item) {
            if (!$item->key instanceof Scalar) {
                continue;
            }

            $key = $item->key->value;

            if (isset($keys[$key])) {
                $context->notice(
                    'array.duplicate_keys',
                    sprintf('Duplicate array key "%s" in array definition (previously declared in line %d).', $key, $keys[$key]->getLine()),
                    $item
                );

                $result = true;
            }

            $keys[$key] = $item;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Expr\Array_::class
        ];
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('array.duplicate_keys')
            ->canBeDisabled()
        ;

        return $treeBuilder;
    }
}
