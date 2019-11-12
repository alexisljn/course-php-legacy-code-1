<?php

namespace App\Repository;

use App\Interfaces\DbConnectInterface;
use App\Models\Users;
use Exception;
use PDO;

class UserRepository implements DbConnectInterface
{
    private $pdo;
    private $table; // A voir

    public function connect()
    {
        try {
            $this->pdo = new PDO(DBDRIVER.':host='.DBHOST.';dbname='.DBNAME, DBUSER, DBPWD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur SQL : '.$e->getMessage());
        }

        $this->table = get_called_class();
    }


    public function getUser()
    {

    }

}