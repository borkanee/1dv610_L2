<?php

namespace Model;

require_once 'exception/EmptySnippetException.php';
require_once 'exception/SnippetNameShortException.php';
require_once 'exception/SnippetCodeMissingException.php';

class Snippet
{
    private $name;
    private $code;

    public function __construct($name, $code)
    {
        if (!$name && !$code) {
            throw new EmptySnippetException();
        }
        if (strlen($name) < 3) {
            throw new SnippetNameShortException();
        }
        if (!$code) {
            throw new SnippetCodeMissingException();
        }

        $this->name = $name;
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }
}
