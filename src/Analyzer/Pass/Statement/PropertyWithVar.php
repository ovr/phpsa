<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Class_;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Pass\ConfigurablePassInterface;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class PropertyWithVar implements ConfigurablePassInterface, AnalyzerPassInterface
{
    /**
     * @param Property $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Property $stmt, Context $context)
    {
        if (!$stmt->isPrivate() && !$stmt->isProtected() && ($stmt->type & Class_::MODIFIER_PUBLIC) === 0) {
            $context->notice(
                'property.var',
                'Class property was defined with the deprecated var keyword. Use a visibility modifier instead',
                $stmt
            );
            return true;
        }
        return false;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('property_with_var')
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
            Property::class
        ];
    }
}
