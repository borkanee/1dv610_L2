<?php

namespace Model;

require_once 'model/Database.php';

class Register
{
    private $db;

    public function __construct()
    {
        $this->db = new \Model\Database();
    }

    public function userExists(NewUser $user): bool
    {
        $name = $user->getName();

        $queryString = "SELECT * FROM users WHERE username='$name'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeUser(NewUser $user): bool
    {
        $name = $user->getName();
        $password = $user->getPassword();

        $queryString = "INSERT INTO users (username, password) VALUES ('$name', '$password')";
        $this->db->queryManage($queryString);
        return true;
    }
}
