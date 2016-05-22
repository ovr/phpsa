<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */


namespace PHPSA;

use Webiny\Component\EventManager\EventManager;

class Analyzer
{
    /**
     * @var EventManager
     */
    protected $em;

    public function __construct(EventManager $em)
    {
        $this->em = $em;
    }
}
