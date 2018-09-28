<?php

namespace Model;

require_once 'config/Config.php';

class Database
{
    private $db;

    public function __construct()
    {
        $this->db = mysqli_connect(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_NAME']
        );

        if (mysqli_connect_error()) {
            die('Connect Error: ' . $mysqli->connect_error);
        }
    }
    public function querySelect($queryString)
    {
       return mysqli_query($this->db, $queryString);
    }
    public function queryManage($queryString)
    {
        mysqli_query($this->db, $queryString);
    }

}
