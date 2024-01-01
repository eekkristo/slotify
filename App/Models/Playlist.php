<?php

namespace App\Models;

use PDO;


/**
 * Model class that deals with everything related to playlist
 * @property $name
 * @property $owner
 * @property $user_id
 * @property $date_created
 *
 */
class Playlist extends \Core\Model
{

    protected $playlistOrder;
    public string $name;
    public string $owner;
    public int $user_id;
    public string $date_created;
    public string $username; 
    public int $id;
    public int $playlistId;
    public int $songId;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    public function insertPlaylist($user)
    {
        
        $sql = 'INSERT INTO playlists (name, owner, user_id, date_created)
                    VALUES (:name, :owner, :user_id, :date_created)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':owner', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user, PDO::PARAM_STR);
        $stmt->bindValue(':date_created', date("Y-m-d H:i:s"), PDO::PARAM_STR);

        return $stmt->execute();

    }

    public static function getPlaylist($owner)
    {
        $sql = 'SELECT * FROM playlists 
        WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $owner, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getPlaylistByPlaylistId($playlist_id)
    {
        $sql = 'SELECT * FROM playlists 
        WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $playlist_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getPlaylistSongs($playlist_id)
    {
        $sql = "SELECT s.title as song_title, s.duration, s.path, s.id as song_id,
        s.album_id, s.genre_id, s.artist_id, a.title as album_title, a.artwork_path,
        g.name as genre, artist.name as artist_name
        from songs s
        left join albums a on a.id = s.album_id
        left join artists artist on s.artist_id = artist.id
        left join genres g on s.genre_id = g.id
        left join playlist_songs p on s.id = p.song_id
        WHERE p.playlist_id = :playlist_id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':playlist_id', $playlist_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function deletePlaylist($owner, $playlist_id)
    {
        $sql = 'DELETE FROM playlists
        WHERE id = :playlist_id AND user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':playlist_id', $playlist_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $owner, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function addToPlaylist()
    {
        $sql = 'SELECT IFNULL(MAX(playlist_order) + 1, 1)  as playlistOrder FROM playlist_songs WHERE playlist_id = :playlist_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':playlist_id', $this->playlistId, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $this->playlistOrder = $stmt->fetch();
    }

    public function savePlaylistSong()
    {
        $this->addToPlaylist();

        $sql = 'INSERT INTO playlist_songs (song_id, playlist_id, playlist_order)
        VALUES (:song_id, :playlist_id, :playlist_order)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':song_id', $this->songId, PDO::PARAM_INT);
        $stmt->bindValue(':playlist_id', $this->playlistId, PDO::PARAM_INT);
        $stmt->bindValue(':playlist_order', $this->playlistOrder->playlistOrder, PDO::PARAM_INT);

        $stmt->execute();
    }

}
