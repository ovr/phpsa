<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass;

interface ConfigurablePassInterface
{
    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public static function getConfiguration();

    /**
     * @return string
     */
    public static function getName();
}
