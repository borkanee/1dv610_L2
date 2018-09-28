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

    public function userExists($username): bool
    {
        $queryString = "SELECT * FROM users WHERE username='$username'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeUser($username, $password, $passwordRepeat)
    {
        if (strlen($username) < 3
            || strlen($password) < 6
            || $password != $passwordRepeat
            || $this->userExists($username)
            || preg_match("/^[a-zA-Z0-9]+$/", $username) == false) {
            return false;
        }
        $queryString = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        $this->db->queryManage($queryString);
        return true;
    }
}
