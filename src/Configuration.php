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
}