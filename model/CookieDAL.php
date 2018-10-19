<?php

namespace Model;

require_once 'model/Database.php';

class CookieDAL
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function cookieIsValid(Cookie $cookie): bool
    {
        $queryString = "SELECT * FROM cookies WHERE username='" . $cookie->getName() . "' AND cookiepassword='" . $cookie->getPassword() . "' AND expirydate>'" . $cookie->getDate() . "'";
        $result = $this->db->querySelect($queryString);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeCookie(Cookie $cookie)
    {
        $queryString = "INSERT INTO cookies (username, cookiepassword, expirydate) VALUES ('" . $cookie->getName() . "', '" . $cookie->getPassword() . "', '" . $cookie->getDate() . "')";
        $this->db->queryManage($queryString);
    }

    public function removeCookie($username)
    {
        $queryString = "DELETE FROM cookies WHERE username='$username'";
        $this->db->queryManage($queryString);
    }
}
