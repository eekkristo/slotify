<?php

namespace App\Models;

use PDO;
use App\Mail;
use App\Token;
use Core\View;

/**
 * Model class that deals with everything related to albums
 * @property array $errors
 * @property $title
 * @property $artist_id
 * @property $genre_id
 * @property $artwork_path
 *
 */
class Album extends \Core\Model
{

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function getAllAlbums($limit = 10)
    {
        $sql = 'SELECT * FROM albums LIMIT ' .$limit;

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getAlbumByUrl($friendly_url)
    {

        $sql = 'SELECT * FROM albums 

        WHERE friendly_url = :friendly_url';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':friendly_url', $friendly_url, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAlbumById($id)
    {
        $sql = 'SELECT * FROM albums 

        WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAlbumByArtistId($id)
    {

        $sql = 'SELECT * FROM albums 
        WHERE artist_id = :artist_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':artist_id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }


    
}
