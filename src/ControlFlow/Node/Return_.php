<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

class Return_ extends AbstractNode
{
    public function willExit()
    {
        return true;
    }
}
