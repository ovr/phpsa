<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass;

interface AnalyzerPassInterface
{
    /**
     * @return array
     */
    public function getRegister();
}
