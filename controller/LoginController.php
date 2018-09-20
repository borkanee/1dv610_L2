<?php

namespace Controller;

class LoginController
{

    private $view;
    private $user;

    public function __construct(\Model\User $user)
    {
        $this->user = $user;
        $this->view = new \View\LoginView($this->user);

    }
    public function doLogin(): string
    {
        if ($this->view->userWantsToLogin()) {
            $name = $this->view->getUserName();
            $this->user->setName($name);
        }
        return $this->view->show();
    }
}
