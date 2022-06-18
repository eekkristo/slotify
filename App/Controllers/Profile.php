<?php

namespace App\Controllers;

use \Core\View;
use \App\Controllers\Authenticated;
use \App\Auth;

/**
 * Home controller
 *
 * PHP version 8
 */
class Profile extends Authenticated
{


    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {

        View::render('Account/Profile/profile.html', [
            'user' => Auth::getUser()
        ]);
    }

    public function editAction()
    {
        View::render('Account/Profile/edit.html', [
            'user' => Auth::getUser()
        ]);
    }

}
