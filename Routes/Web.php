<?php

use \Core\Router;

/**
 * Routing
 */
$router = new Router();

/* 
 * All routes have to come from here
 * Exception to this is only from stateless routes, e.g api calls
 * For that we use the Api.php file and define our routes there
*/

// Root routes with views in Home.php Controller
$router->add('', ['controller' => 'Home', 'action' => 'index']); // root url

// Login route
$router->add('login', ['controller' => 'Login', 'action' => 'new']); // root/login/
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']); // root/logout/

// Register route
$router->add('{controller}/{action}');

//User Profile route
$router->add('profile', ['controller' => 'Profile', 'action' => 'index']);
$router->add('profile/edit', ['controller' => 'Profile', 'action' => 'edit']);

// Forgot password route
$router->add('password', ['controller' => 'Password', 'action' => 'forgot']);
// Reset password route & token
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);

// Email activation route
$router->add('register/activate/{token:[\da-f]+}', ['controller' => 'Register', 'action' => 'activate']);

// Browse list of albums route
$router->add('browse', ['controller' => 'Browse', 'action' => 'index']);
// Browse single album
$router->add('browse/album/{album:[\a-z]+}', ['controller' => 'Browse', 'action' => 'single']);
// Browse artist songs
$router->add('browse/artist/{artist:[\a-z]+}', ['controller' => 'Browse', 'action' => 'getSingleArtist']);
// Search
$router->add('search', ['controller' => 'Search', 'action' => 'index']);
// Get search
$router->add('search/content/{search:[\a-z]+}', ['controller' => 'Search', 'action' => 'getSearch']);

// Playlist
$router->add('playlist', ['controller' => 'MyPlaylist', 'action' => 'index']);
$router->add('playlist/{owner:[\da-f]+}/{playlist:[\a-z]+}', ['controller' => 'MyPlaylist', 'action' => 'single']);


// Admin Routes
// Admin panel
$router->add('admin', ['namespace' => 'Admin', 'controller' => 'Panel', 'action' => 'index']); // root/admin/panel/index

/* 
* End of routes file
* Last sentence is required to actually dispatch (be visible for the end user the data)
* TODO: Implement Dispatch.php file inside Routes file that fill handle all of this data so we do not have to define this ourselces
*/

// Dispatch the routes 
$router->dispatch($_SERVER['QUERY_STRING']);
