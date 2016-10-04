<?php
/**
 * @author Leonardo da Mata https://github.com/barroca <barroca@gmail.com>
 */
namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Property;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class HasMoreThanOneProperty implements Pass\ConfigurablePassInterface, Pass\AnalyzerPassInterface
{
    /**
     * @param Property $prop
     * @param Context $context
     * @return bool
     */
    public function pass(Property $prop, Context $context)
    {
        if (count($prop->props) > 1) {
            $context->notice('limit.properties', 'Number of properties larger than one.', $prop);
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
        $treeBuilder->root('limit.properties')
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
            Property::class,
        ];
    }
}
