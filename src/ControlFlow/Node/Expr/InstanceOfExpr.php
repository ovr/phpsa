<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node\Expr;

use PHPSA\ControlFlow\Node\AbstractNode;

class InstanceOfExpr extends AbstractNode
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
