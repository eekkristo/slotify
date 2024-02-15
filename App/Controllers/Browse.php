<?php

//FIXME: Seperate Album and Artist Controllers to seperate files
namespace App\Controllers;

use \Core\View;
use \App\Models\Album;
use App\Models\Artist;
use \App\Models\Song;



/**
 * Home controller
 *
 * PHP version 8
 */
class Browse extends Authenticated
{

    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
     
    }

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

        
        $album = Album::getAllAlbums();

        View::render('Browse/browse.html', [
            'albums' => $album,
            'random_songs' => Song::playRandomSong()
        ]);
        
        echo "<script>console.log('testFromIndexAction');</script>"; //
  
    }

    public function singleAction()
    {

        $url = $this->route_params['album'];
        $album = Album::getAlbumByUrl($url);
        if ($album) {

            $albumSongs = Song::getAllSongsByAlbum($album->id);
            $albumTotal = Song::countAlbumSongs($album->id);

            $songIdArray = array();
            // Return song id as array here TODO: Make this better
            foreach ($albumSongs as $albumSongId) {
                array_push($songIdArray, $albumSongId->song_id);
            }

            View::render('Browse/Album/album.html',[
                'songs' => $albumSongs, // In here all songs for that album should be present
                'album' => $album,
                'total_songs' => $albumTotal,
                'song_ids' => $songIdArray,
                
            ]);
        } else {
            echo "Album not found";
        }
        
    }

    public function getSingleArtistAction()
    {

        $url = $this->route_params['artist'];
        $artist = Artist::getArtistByUrl($url);

        if ($artist) {
            
            $artistSongs = Song::getAllSongsByArtist($artist->id);
            $artistAlbums = Album::getAlbumByArtistId($artist->id);

            $songIdArray = array();
            // Return song id as array here TODO: Make this better and
            foreach ($artistSongs as $artistSongId) {
                array_push($songIdArray, $artistSongId->song_id);
            }

            View::render('Browse/Artist/artist.html', [
                'artist' => $artist,
                'songs' => $artistSongs,
                'albums' => $artistAlbums,
                'song_ids' => $songIdArray
            ]);

        } else {
            // TODO: Implement 404 artist not found designed page
            echo "Artist not found";
        }

  
    }

}
