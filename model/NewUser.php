<?php

namespace Model;

require_once 'exception/PasswordsNotMatchException.php';
require_once 'exception/UserNameShortException.php';
require_once 'exception/PasswordShortException.php';
require_once 'exception/InvalidCharactersException.php';
require_once 'exception/EmptyRegistrationException.php';

class NewUser
{
    private $name;
    private $password;

    public function __construct($name, $password, $passwordRepeat)
    {
        if (!$name && !$password && !$passwordRepeat) {
            throw new EmptyRegistrationException();
        }
        if (preg_match("/^[a-zA-Z0-9]+$/", $name) == false) {
            throw new InvalidCharactersException();
        }
        if (strlen($name) < 3) {
            throw new UserNameShortException();
        }
        if (strlen($password) < 6) {
            throw new PasswordShortException();
        }
        if ($password != $passwordRepeat) {
            throw new PasswordsNotMatchException();
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
