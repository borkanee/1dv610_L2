<?php

namespace Model;

class UserCredentials
{
    private $name;
    private $password;

    public function __construct(Name $name, Password $password)
    {
        $this->name = $name;
        $this->password = $password;
    }
}
