<?php

namespace App\Models;

use PDO;
use App\Mail;
use App\Token;
use Core\View;

/**
 * Model class that deals with everything related to artist
 * @property array $errors
 * @property $title
 * @property $artist_id
 * @property $genre_id
 * @property $artwork_path
 *
 */
class Artist extends \Core\Model
{

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    public static function getArtistByUrl($url)
    {
        $sql = 'SELECT * FROM artists WHERE friendly_url = :friendly_url';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':friendly_url', $url, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getArtistById($id)
    {
        $sql = 'SELECT * FROM artists WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetch();
    }

}
