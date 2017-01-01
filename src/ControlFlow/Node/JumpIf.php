<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

class JumpIf extends \PHPSA\ControlFlow\Graph\Block
{
    protected $if;

    protected $else;

    /**
     * @param mixed $if
     */
    public function setIf($if)
    {
        $this->if = $if;
    }

    /**
     * @param mixed $else
     */
    public function setElse($else)
    {
        $this->else = $else;
    }

    public function getSubBlocks()
    {
        return [
            'if' => $this->if,
            'else' => $this->else,
        ];
    }
}
