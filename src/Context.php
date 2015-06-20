<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 20.06.15
 * Time: 16:28
 */

namespace PHPSA;

use PHPSA\Definition\ClassDefinition;

class Context
{
    /**
     * @var ClassDefinition
     */
    public $scope;

    /**
     * @var Application
     */
    public $application;
}