<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;

class TraitDefinition extends ParentDefinition
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * Compile the definition
     *
     * @param Context $context
     * @return boolean
     */
    public function compile(Context $context)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }
}
