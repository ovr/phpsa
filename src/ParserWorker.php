<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 13.09.15
 * Time: 19:47
 */

namespace PHPSA;


use PhpParser\Parser;

/**
 * @property Compiler compiler
 */
class ParserWorker extends \Worker
{
    public function __construct(Parser $parser, Compiler $compiler) {
        $this->parser = $parser;
        $this->compiler = $compiler;
    }

    public function run() {

    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @return Compiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }
}
