<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow;

class Graph
{
    protected $entryPoint;

    protected $nodes;

    public function __construct()
    {
        $this->nodes = new \SplObjectStorage();
    }
}
