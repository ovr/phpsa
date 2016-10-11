<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;

class ArrayDuplicateKeys implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = <<<DESC
This inspection reports any duplicated keys on array creation expression.
If multiple elements in the array declaration use the same key, only the last
one will be used as all others are overwritten.
DESC;

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
            $compiledKey = $context->getExpressionCompiler()->compile($item->key);
            if (!$compiledKey->isTypeKnown() || !$compiledKey->isScalar() || !$compiledKey->hasValue()) {
                continue;
            }

            $key = $compiledKey->getValue();

            if (isset($keys[$key])) {
                $context->notice(
                    'array.duplicate_keys',
                    sprintf(
                        'Duplicate array key "%s" in array definition (previously declared in line %d).',
                        $item->key instanceof Expr\Variable ? sprintf('$%s (resolved value: "%s")', $item->key->name, $key) : $key,
                        $keys[$key]->getLine()
                    ),
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
}
