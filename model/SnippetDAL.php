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

        $snippets = [];

        if ($result !== false) {

            while ($row = $result->fetch_object()) {
                array_push($snippets, $row);
            }
        }
        return array_reverse($snippets); // Reverse so that latest snippet is on top.
    }
}
