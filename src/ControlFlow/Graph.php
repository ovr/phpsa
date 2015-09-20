<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow;

class Graph
{
    /**
     * @var object
     */
    protected $entryPoint;

    /**
     * @var \SplObjectStorage
     */
    protected $nodes;

    /**
     * @param $entryPoint
     */
    public function __construct($entryPoint)
    {
        $this->entryPoint = $entryPoint;
        $this->nodes = new \SplObjectStorage();
    }
}
