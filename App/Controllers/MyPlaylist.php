<?php

namespace App\Controllers;

use \Core\View;

use \App\Models\User;
use \App\Models\Playlist;

/**
 * Home controller
 *
 * PHP version 8
 */
class MyPlaylist extends \Core\Controller
{

    /**
     * Show the Playlist html page
     * We take session id from the user and use it to create a new playlist
     *  
     * @return mixed
     */
    public function indexAction()
    {
        $owner = $_SESSION['user'];
        $playlist = New Playlist();
        $user_playlist = $playlist::getPlaylist($owner);

        View::render('Playlist/playlist.html',[
            'playlists' => $user_playlist 
        ]);
    }

    /**
     * Returns single playlist where all of the tracks are for that specific playlist
     *
     * @return mixed
     */
    public function singleAction()
    {
        $playlistId = $this->route_params['playlist'];

        //echo $playlistId;

        $data = Playlist::getPlaylistSongs($playlistId);
        $playlist = Playlist::getPlaylistByPlaylistId($playlistId);

        View::render('Playlist/single_playlist.html', [
            'playlist_songs' => $data,
            'playlist' => $playlist
        ]);
       
    }
}
