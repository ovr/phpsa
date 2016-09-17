<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Helper;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

trait ConfigurablePassTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        $fqcnParts = explode('\\', __CLASS__);

        return self::convertToSnakeCase(end($fqcnParts));
    }

    /**
     * {@inheritdoc}
     */
    public static function getConfiguration()
    {
        $treeBuilder = new TreeBuilder();

        return $treeBuilder->root(self::getName())
            ->canBeDisabled()
        ;
    }

    /**
     * @param string $string
     * @return string
     */
    private static function convertToSnakeCase($string)
    {
        $snakeCasedName = '';
        $len = strlen($string);
        for ($i = 0; $i < $len; ++$i) {
            if ($i !== 0 && ctype_upper($string[$i])) {
                $snakeCasedName .= '_'.strtolower($string[$i]);
            } else {
                $snakeCasedName .= strtolower($string[$i]);
            }
        }
        return $snakeCasedName;
    }
}
