<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Helper;

class Inflector
{
    /**
     * Converts a CamelCase string to its snake_case equivalent.
     *
     * @param string $string
     * @return string
     */
    public static function convertToSnakeCase($string)
    {
        $result = '';
        $len = strlen($string);

        for ($i = 0; $i < $len; ++$i) {
            if ($i !== 0 && ctype_upper($string[$i])) {
                $result .= '_'.strtolower($string[$i]);
            } else {
                $result .= strtolower($string[$i]);
            }
        }

        return $result;
    }
}
