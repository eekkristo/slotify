<?php

namespace App\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use App\Models\Search;
use App\Models\Playlist;

/**
 * Ajax controller 
 * This controller deals with everthing related to Ajax calls. We should only use this class for this as this holds all of the functions.
 * TODO: Implement a $_POST check that deals with the data charset escape. Since js calls can be changed we should always assume the worst
 * @uses Routes\Api\Api.php Route
 * Always return all of the data with json_encode for js to read it correctly. Returning it any other way will return it void and throw an error
 * TODO: Implement a global json_encode parameter so that we do not have to render it ourselves constantly. Create a function for it and call it that way?
 * @return mixed
 * 
 * PHP version 8
 */

class Ajax extends \Core\Controller
{

    public function getSongAction()
    {
        if (isset($_POST['songId'])) {
            
            echo json_encode(Song::getSong($_POST['songId']));
            
        } 
    }

    public function getArtistAction()
    {
        if (isset($_POST['artistId'])) {
            
            echo json_encode(Artist::getArtistById($_POST['artistId']));
            
        } 
    }


    public function getAlbumAction()
    {
        if (isset($_POST['albumId'])) {
            
            echo json_encode(Album::getAlbumById($_POST['albumId']));
            
        } 
    }

    public function trackCountUpdateAction()
    {
        if (isset($_POST['songId'])) {
            Song::updateTrackCount($_POST['songId']);
        }
    }

    public function searchArtistAction()
    {
        if (isset($_POST['searchArtist'])) {
            $request = New Search();
            echo json_encode($request->search($_POST['searchArtist'])->artist());
        }
    }

    public function searchSongAction()
    {
        if (isset($_POST['searchSong'])) {
            $request = New Search();
            echo json_encode($request->search($_POST['searchSong'])->song());
        }
    }

    public function searchAlbumAction()
    {
        if (isset($_POST['searchAlbum'])) {
            $request = New Search();
            echo json_encode($request->search($_POST['searchAlbum'])->album());
        }
    }

    /**
     * Creates an user playlist. 
     * We need to make sure that we get the $_POST user and username. 
     * We need to check each unique user_id with owner so that in the future we can use friendly-url and allow users to share their playlists with each other
     *
     * @return void
     */
    
    public function createPlaylistAction()
    {
        if (isset($_POST['name']) && isset($_POST['username'])) {
            $user = $_SESSION['user'];
            $playlist = New Playlist($_POST);
            echo json_encode($playlist->insertPlaylist($user));
        }
    }

    public function deletePlaylistAction()
    {
        if (isset($_POST['playlistId'])) {
            
            $user = $_SESSION['user'];
            $playlist = New Playlist($_POST);
            $playlist::deletePlaylist($user, $_POST['playlistId']);
        }
    }

    public function getPlaylists()
    {
        if (isset($_POST['getPlaylists'])) {
        //echo "<script>window.alert('hi');</script>";

            $user = $_SESSION['user'];
            $playlist = New Playlist();
            echo json_encode($playlist::getPlaylist($user));
        }
    }

    public function addSongToPlaylistAction()
    {
        if (isset($_POST['playlistId'])) {
            $save = New Playlist($_POST);
            $user = $_SESSION['user'];
            $save->savePlaylistSong();

        }

    }

    public function deleteSongAction()
    {
        if (isset($_POST['deleteSong'])) {
            echo "<script>console.log('test');</script>";
        }
    }

}
