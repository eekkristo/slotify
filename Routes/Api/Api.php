<?php

namespace Api;

use \Core\Router;

/**
 * Routing for API
 * Stateless use
 * The difference with Web.php routes is that we do not dispatch the view. E.g these routes are available 
 * but they do not show any data on the client-side
 * Will be later modified to provide rest end points that don't require regular authentication
 * Adding Web.php routes here and navigating there will render an error.
 */
$router = new Router();

// Add the routes
// Now playing bar Routes
// AJAX requests
// Get song
$router->add('ajax/getSong', ['controller' => 'Ajax', 'action' => 'getSong']);
// Get artist
$router->add('ajax/getArtist', ['controller' => 'Ajax', 'action' => 'getArtist']);
$router->add('ajax/getAlbum', ['controller' => 'Ajax', 'action' => 'getAlbum']);
$router->add('ajax/trackCountUpdate', ['controller' => 'Ajax', 'action' => 'trackCountUpdate']);
$router->add('ajax/deletePlaylist', ['controller' => 'Ajax', 'action' => 'deletePlaylist']);
$router->add('ajax/getPlaylists', ['controller' => 'Ajax', 'action' => 'getPlaylists']);
$router->add('ajax/addSongToPlaylist', ['controller' => 'Ajax', 'action' => 'addSongToPlaylist']);
$router->add('ajax/deleteSong', ['controller' => 'Ajax', 'action' => 'deleteSong']);
