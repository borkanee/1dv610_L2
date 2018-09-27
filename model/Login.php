<?php

namespace Model;

require_once 'config/Config.php';

class Login
{
    private static $SESSION_KEY = __NAMESPACE__ . __CLASS__ . "userLoggedIn";
    private $db;

    public function __construct()
    {
        //TODO: Move DB to own class...
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

    public function userExists($username, $password): bool
    {
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($this->db, $query);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function userExistsReg($username): bool
    {
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($this->db, $query);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    //TODO: Maybe move Cookie storage to own class as well...
    public function cookiesAreValid($username, $cookiePassword, $date): bool
    {
        $query = "SELECT * FROM cookies WHERE username='$username' AND cookiepassword='$cookiePassword' AND expirydate>'$date'";
        $result = mysqli_query($this->db, $query);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeCookies($username, $cookiePassword, $expiryDate)
    {
        $insert = "INSERT INTO cookies (username, cookiepassword, expirydate) VALUES ('$username', '$cookiePassword', '$expiryDate')";

        mysqli_query($this->db, $insert);
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
    public function removeCookies($username)
    {
        $delete = "DELETE FROM cookies WHERE username='$username'";
        mysqli_query($this->db, $delete);
    }

}
