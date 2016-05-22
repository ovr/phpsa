<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

interface ConfigurablePassInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfiguration();
}
