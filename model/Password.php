<?php

namespace Model;

class Password
{
    private $password;

    public function __construct(string $password)
    {
        // ADD CORRECT EXCEPTIONS...
        if (strlen($password) < 6) {
            throw new Exeption();
        }
        $this->password = $password;
    }
}
