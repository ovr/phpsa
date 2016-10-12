<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class ArrayShortDefinition implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait {
        DefaultMetadataPassTrait::getMetadata as defaultMetadata;
    }

    const DESCRIPTION = 'Recommends the use of [] short syntax for arrays.';

    /**
     * @param Expr\Array_ $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\Array_ $expr, Context $context)
    {
        if ($expr->getAttribute('kind') == Expr\Array_::KIND_LONG) {
            $context->notice(
                'array.short-syntax',
                'Please use [] (short syntax) for array definition.',
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
            Expr\Array_::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getMetadata()
    {
        $metadata = self::defaultMetadata();
        $metadata->setRequiredPhpVersion('5.4');

        return $metadata;
    }
}
