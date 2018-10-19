<?php

namespace Model;

require_once 'exception/NameAndPasswordMissingException.php';
require_once 'exception/PasswordMissingException.php';
require_once 'exception/UserNameMissingException.php';

class UserCredentials
{
    private $name;
    private $password;

    public function __construct($name, $password)
    {
        if (!$name && !$password) {
            throw new NameAndPasswordMissingException();
        }
        if (!$password) {
            throw new PasswordMissingException();
        }
        if (!$name) {
            throw new UserNameMissingException();
        }

        $this->name = $name;
        $this->password = $password;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
