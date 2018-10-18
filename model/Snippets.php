<?php

namespace Model;

require_once 'model/Database.php';

class Snippets
{
    private $db;

    public function __construct()
    {
        $this->db = new \Model\Database();
    }

    public function storeSnippet($username, $snippetName, $snippetCode)
    {
        $queryString = "INSERT INTO snippets (username, snippetname, snippetcode) VALUES ('$username', '$snippetName', '$snippetCode')";
        $this->db->queryManage($queryString);
    }

    public function getSnippets($username)
    {
        $queryString = "SELECT snippetname, snippetcode FROM snippets WHERE username='$username'";
        $result = $this->db->querySelect($queryString);
        
        $snippetArray = [];

        while ($row = $result->fetch_object()) {
            array_push($snippetArray, $row);
        }
        return $snippetArray;
    }
}
