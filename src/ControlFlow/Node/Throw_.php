<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

class Throw_ extends AbstractNode
{
    /**
     * {@inheritdoc}
     */
    public function willExit()
    {
        return true;
    }
}
