<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class Configuration
{
    protected $config = array(
        'unused' => array(
            'variable' => true,
        ),
        'undefined' => array(
            'mcall' => true,
            'fcall' => true,
            'scall' => true,
            'property' => true,
            'class-const' => true,
            'const' => true,
            'variable' => true,
        ),
        'bugs' => array(
            'division-zero' => true
        )
    );

    protected $values = array(
        'blame' => false
    );

    public function setValue($name, $value)
    {
        $this->values[$name] = $value;
    }

    public function valueIsTrue($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name] == true;
        }

        return false;
    }
}
