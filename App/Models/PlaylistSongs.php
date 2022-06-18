<?php

namespace App\Models;

use PDO;


/**
 * Model class that deals with everything related to playlist songs 
 * @property array $errors
 * @property $song_id
 * @property $playlist_id
 * @property $playlist_order
 *
 */
class Playlist extends \Core\Model
{

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function getPlaylistSongs($song_id)
    {
        $sql = 'SELECT * FROM playlist_songs 
        WHERE song_id = :song_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':song_id', $song_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
