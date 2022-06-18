<?php

namespace App\Controllers;

abstract class Authenticated extends \Core\Controller
{
    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        $this->requireLogin();


                
    }

}