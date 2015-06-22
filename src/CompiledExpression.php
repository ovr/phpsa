<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class CompiledExpression
{
    const UNKNOW_TYPE = 0;

    const LNUMBER = 1;

    const DNUMBER = 2;

    const STRING = 3;

    const BOOLEAN = 3;

    protected $type = self::UNKNOW_TYPE;

    protected $value;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}