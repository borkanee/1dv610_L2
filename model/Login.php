<?php

namespace Model;

require_once 'model/Database.php';

class Login
{
    private static $SESSION_KEY = __NAMESPACE__ . __CLASS__ . "userLoggedIn";
    private $db;

    public function __construct()
    {
        $this->db = new \Model\Database();
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION[self::$SESSION_KEY]) && $_SESSION[self::$SESSION_KEY] === true;
    }

    public function setLoggedIn()
    {
        $_SESSION[self::$SESSION_KEY] = true;
    }

    public function setLoggedOut()
    {
        $_SESSION = array();
    }

    public function userExists($username, $password): bool
    {
        $queryString = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
