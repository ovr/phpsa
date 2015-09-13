<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 13.09.15
 * Time: 0:24
 */

namespace PHPSA;

use Exception;

class ThreadQueue {

    const DEFAULT_QUEUE_SIZE = 2;		// default number of parallel tasks
    const TICK_DELAY = 0;		// delay after tick, in millisecs

    private $callable;	// the function name to call. can be a method of a static class as well
    private $threads = array();	// Thread instances
    private $jobs = array();	// parameters to pass to $callable
    public $queueSize;		// number of parallel tasks. public, to make it variable run-time.


    /**
     *	Constructor
     *
     *	@param string $callable		function name
     *	@param integer $queueSize	number of parallel tasks
     */

    public function __construct($callable, $queueSize = ThreadQueue::DEFAULT_QUEUE_SIZE ){
        if(!is_callable($callable))throw new Exception("$callable is not callable.");
        $this->callable = $callable;
        $this->queueSize = $queueSize;
    }


    /**
     *	Add a new job
     *
     *	@param mixed $argument	parameter to pass to $callable
     *	@return int	queue size
     */

    public function add($argument){
        $this->jobs[] = $argument;
        return $this->tick();
    }


    /**
     *	Removes closed threads from queue
     *
     */

    private function cleanup(){
        foreach($this->threads as $i => $szal)
            if(!$szal->isAlive())
                unset($this->threads[$i]);
        return count($this->threads);
    }


    /**
     *	Starts new threads if needed
     *
     *	@return int	queue size
     */

    public function tick(){
        $this->cleanup();

        if( (count($this->threads) < $this->queueSize) && count($this->jobs) ){
            $this->threads[] = $szal = new Thread($this->callable);
            $szal->start( array_shift($this->jobs) );
        }

        usleep(ThreadQueue::TICK_DELAY);
        return $this->queueSize();
    }
    /**
     *	returns queue size with waiting jobs
     *
     *	@return int
     */

    public function queueSize(){
        return count($this->jobs);
    }


    /**
     *	returns thread instances
     *
     *	@return array of Thread
     */
    public function threads(){
        return $this->threads;
    }


    /**
     *	Removes all remaining jobs (empty queue)
     *
     *	@return int	number of removed jobs
     */
    public function flush(){
        $size = $this->queueSize();
        $this->jobs = array();
        return $size;
    }

}
