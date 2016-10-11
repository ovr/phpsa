<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass;

interface AnalyzerPassInterface
{
    /**
     * @return Metadata
     */
    public static function getMetadata();

    /**
     * @return array
     */
    public function getRegister();
}
