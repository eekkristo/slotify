<?php

namespace App\Controllers\Admin;


/**
 * User admin controller
 *
 * PHP version 8
 */
class Panel extends \App\Controllers\Authenticated
{

    public function indexAction()
    {
        echo 'User admin index';
    }
}
