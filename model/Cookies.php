<?php

namespace Model;

class Cookies
{
    private $db;

    public function __construct()
    {
        $this->db = new \Model\Database();
    }

    public function cookiesAreValid($username, $cookiePassword, $date): bool
    {
        $queryString = "SELECT * FROM cookies WHERE username='$username' AND cookiepassword='$cookiePassword' AND expirydate>'$date'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeCookies($username, $cookiePassword, $expiryDate)
    {
        $queryString = "INSERT INTO cookies (username, cookiepassword, expirydate) VALUES ('$username', '$cookiePassword', '$expiryDate')";
        $this->db->queryManage($queryString);
    }

    public function removeCookies($username)
    {
        $queryString = "DELETE FROM cookies WHERE username='$username'";
        $this->db->queryManage($queryString);
    }
}