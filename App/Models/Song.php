<?php

namespace App\Models;

use PDO;
use App\Mail;
use App\Token;
use Core\View;

/**
 * Model class that deals with everything related to song
 * @property array $errors
 * @property $title
 * @property $artist_id
 * @property $genre_id
 * @property $artwork_path
 *
 */
class Song extends \Core\Model
{
    public string $title;
    public string $arrist_id;
    public string $genre_id;
    public string $artwork_path;
    public string $song_title;
    public string $duration;
    public string $path;
    public string $song_id;
    public string $album_id;
    public string $artist_id;
    public string $album_title;
    public string $genre;
    public string $artist_name;

    public array $errors;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    public static function getAllSongsByAlbum($id)
    {
        $sql = 'SELECT s.title as song_title, s.duration, s.path, s.id as song_id,
        s.album_id, s.genre_id, s.artist_id, a.title as album_title, a.artwork_path,
        g.name as genre, artist.name as artist_name
        from songs s
        left join albums a on a.id = s.album_id
        left join artists artist on s.artist_id = artist.id
        left join genres g on s.genre_id = g.id
       	WHERE s.album_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllSongsByArtist($id, $limit = 5)
    {
        $sql = "SELECT s.title as song_title, s.duration, s.path, s.id as song_id,
        s.album_id, s.genre_id, s.artist_id, a.title as album_title, a.artwork_path,
        g.name as genre, artist.name as artist_name
        from songs s
        left join albums a on a.id = s.album_id
        left join artists artist on s.artist_id = artist.id
        left join genres g on s.genre_id = g.id
       	WHERE artist.id = :id LIMIT {$limit}";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countAlbumSongs($id)
    {

        $sql = 'SELECT COUNT(*) AS total FROM songs WHERE album_id = :id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public static function playRandomSong()
    {
        $sql = 'SELECT id FROM songs ORDER BY RAND() LIMIT 10';

        $resultArray = array();

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($resultArray, $row['id']);
        }

        $jsonArray = json_encode($resultArray);
        //var_dump(json_encode($stmt->fetchAll(PDO::FETCH_NUM)));
        //var_dump($jsonArray);
        return $jsonArray;
    }

    public static function getSong($id)
    {
        $sql = 'SELECT * FROM songs WHERE id = :id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id',$id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function updateTrackCount($id)
    {
        $sql = 'UPDATE songs 
                SET plays = plays + 1
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute(); 
    }

    
}
