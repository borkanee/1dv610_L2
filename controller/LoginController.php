<?php

namespace Controller;

class LoginController
{

    private $view;
    private $user;

    public function __construct(\Model\User $user, \View\LoginView $view)
    {
        $this->user = $user;
        $this->view = $view;

    }

    public function doLogin(): string
    {
        if ($this->view->userWantsToLogin()) {
			$name = $this->view->getUserName();
			$password = $this->view->getUserPassword();

			$this->user->setCredentials($name, $password);
        }

        return $this->view->show();
    }
}
