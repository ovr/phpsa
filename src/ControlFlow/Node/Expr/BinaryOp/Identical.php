<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node\Expr\BinaryOp;

use PHPSA\ControlFlow\Node\AbstractNode;

class Identical extends AbstractNode
{
    public $left;

    public $right;

    public $result;

    public function getSubVariables()
    {
        return [
            'left' => $this->left,
            'right' => $this->right,
            'result' => $this->result
        ];
    }
}
