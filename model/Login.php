<?php

namespace Model;

require_once 'model/Database.php';

class Login
{
    private static $SESSION_LOGGEDIN = __NAMESPACE__ . __CLASS__ . "userLoggedIn";
    private static $SESSION_USER = __NAMESPACE__ . __CLASS__ . "username";

    private $db;

    public function __construct()
    {
        $this->db = new \Model\Database();
    }

    public function getSessionUser()
    {
        return $_SESSION[self::$SESSION_USER];
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION[self::$SESSION_LOGGEDIN]) && $_SESSION[self::$SESSION_LOGGEDIN] === true;
    }

    public function setLoggedIn($username)
    {
        $_SESSION[self::$SESSION_LOGGEDIN] = true;
        $_SESSION[self::$SESSION_USER] = $username;
    }

    public function setLoggedOut()
    {
        $_SESSION = array();
    }

    public function userExists(UserCredentials $userCredentials): bool
    {
        $username = $userCredentials->getName();
        $password = $userCredentials->getPassword();

        $queryString = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
