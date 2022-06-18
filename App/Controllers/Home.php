<?php

namespace App\Controllers;


use \Core\View;

/**
 * Home controller
 *
 * PHP version 8
 */
class Home extends Authenticated
{

    /**
     * Show the index page
     *
     * @return void
     */

    public function indexAction()
    {

        View::render('Home/index.html', [
            'title'    => 'Simplyframe',
            'url' => 'https://github.com/eekkristo/simplyframe',
            'authors' => ['Kristo', 'and', 'Santa'],
        ]);

    }

    public function aboutAction()
    {
        View::render('Home/about.html');
    }

}
