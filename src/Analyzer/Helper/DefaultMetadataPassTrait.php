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

        // static:: because AbstractFunctionCallAnalyzer implement DefaultMetadataPassTrait and is a parent class
        if (defined('static::DESCRIPTION')) {
            $description = static::DESCRIPTION;
        }

        return Metadata::create($name, $description);
    }
}
