<?php

namespace Model;

class Username
{
    private $name;

    public function __construct(string $name)
    {
        // ADD CORRECT EXCEPTIONS...
        if (strlen($name) < 3) {
            throw new Exeption();
        }
        $this->name = $name;
    }
}
