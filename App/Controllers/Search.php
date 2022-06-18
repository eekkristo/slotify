<?php

namespace App\Controllers;


use \Core\View;

/**
 * Home controller
 *
 * PHP version 8
 */
class Search extends Authenticated
{

    /**
     * Show the index page
     *
     * @return void
     */

    public function indexAction()
    {
        View::render('Browse/Search/search.html');

    }

    public function getSearchAction()
    {
        echo $this->route_params['content'];

        
    }

}
