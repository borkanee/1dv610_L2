<?php

namespace View;

class Message
{
    public function wrongCredentials()
    {
        return 'Wrong name or password';
    }

    public function userExists()
    {
        return 'User exists, pick another username.';
    }

    public function welcome()
    {
        return 'Welcome';
    }

    public function welcomeRemembered()
    {
        return 'Welcome and you will be remembered';
    }

    public function bye()
    {
        return 'Bye bye!';
    }

    public function welcomeBackCookie()
    {
        return 'Welcome back with cookie';
    }

    public function wrongCookie()
    {
        return 'Wrong information in cookies';
    }

    public function registeredUser()
    {
        return 'Registered new user.';
    }
}
