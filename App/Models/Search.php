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
class Search extends \Core\Model
{

    private $search;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function search($input)
    {
        $this->search = $input;
        return $this;
    }

    public function artist()
    {
        $sql = "SELECT * FROM artists 
        WHERE name LIKE CONCAT('%', :name, '%')";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $this->search, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function song()
    {
        $sql = "SELECT * FROM songs 
        WHERE title LIKE CONCAT('%', :title, '%')";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':title', $this->search, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function album()
    {
        $sql = "SELECT * FROM albums 
        WHERE title LIKE CONCAT('%', :title, '%')";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':title', $this->search, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }


    
}
