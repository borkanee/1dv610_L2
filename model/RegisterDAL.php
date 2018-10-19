<?php

namespace Model;

require_once 'model/Database.php';

class RegisterDAL
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function userExists(NewUser $user): bool
    {
        $queryString = "SELECT * FROM users WHERE username='" . $user->getName() . "'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeUser(NewUser $user): bool
    {
        $queryString = "INSERT INTO users (username, password) VALUES ('" . $user->getName() . "', '" . $user->getPassword() . "')";
        $this->db->queryManage($queryString);
        return true;
    }
}
