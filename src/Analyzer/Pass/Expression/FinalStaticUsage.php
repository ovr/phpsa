<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;

class FinalStaticUsage implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait {
        DefaultMetadataPassTrait::getMetadata as defaultMetadata;
    }

    const DESCRIPTION = 'Checks for use of `static::` inside a final class.';

    /**
     * @param Expr\StaticCall $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\StaticCall $expr, Context $context)
    {
        $classObject = $context->scope->getPointer()->getObject();
        if (!$classObject instanceof ClassDefinition || !$classObject->isFinal()) {
            return false;
        }

        if ($expr->class->getFirst() !== 'static') {
            return false;
        }

        $context->notice(
            'error.final-static-usage',
            'Don\'t use static:: in final class',
            $expr
        );

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Expr\StaticCall::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getMetadata()
    {
        $metadata = self::defaultMetadata();
        $metadata->setRequiredPhpVersion('5.3'); //static:: since PHP 5.3

        return $metadata;
    }
}
