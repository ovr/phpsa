<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Graph;

class Edge
{
    /**
     * @var Node
     */
    protected $source;

    /**
     * @var Node|null
     */
    protected $destination;

    /**
     * @var integer
     */
    protected $type;

    public function __construct(Node $source, Node $destination = null, $type = -1)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->type = $type;
    }
}
