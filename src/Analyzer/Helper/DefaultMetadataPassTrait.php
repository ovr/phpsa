<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Helper;

use PHPSA\Analyzer\Pass\Metadata;

trait DefaultMetadataPassTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getMetadata()
    {
        $fqcnParts = explode('\\', get_called_class());
        $name = Inflector::convertToSnakeCase(end($fqcnParts));
        $description = null;

        if (defined('self::DESCRIPTION')) {
            $description = self::DESCRIPTION;
        }

        return Metadata::create($name, $description);
    }
}
