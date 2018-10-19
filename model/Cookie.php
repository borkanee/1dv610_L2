<?php

namespace Model;

class Cookie
{
    private $name;
    private $password;
    private $date;

    public function __construct($name, $password, $date)
    {
        $this->name = $name;
        $this->password = $password;
        $this->date = $date;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDate()
    {
        return $this->date;
    }
}
