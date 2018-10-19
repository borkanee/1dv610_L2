<?php

namespace Model;

require_once 'model/Snippet.php';

class SnippetDAL
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function storeSnippet(Snippet $snippet, $username)
    {
        $queryString = "INSERT INTO snippets (username, snippetname, snippetcode) VALUES ('$username', '" . $snippet->getName() . "', '" . $snippet->getCode() . "')";
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
        return array_reverse($snippetArray);
    }
}
