<?php

namespace Controller;

class RegisterController
{
    private $registerModel;
    private $registerView;

    public function __construct(\model\Register $registerModel, \view\RegisterView $registerView)
    {
        $this->registerModel = $registerModel;
        $this->registerView = $registerView;
    }

    public function manageRegistration(): void
    {
        if ($this->registerView->userWantsToRegister()) {
            $username = $this->registerView->getUserName();
            $password = $this->registerView->getUserPassword();
            $passwordRepeat = $this->registerView->getUserPasswordRepeat();

            $successfulRegistration = $this->registerModel->storeUser($username, $password, $passwordRepeat);

            if ($successfulRegistration) {
                unset($_GET["register"]);
                header("Location: http://611afbea.ngrok.io/1dv610_L2/");
                $_SESSION['registeredUser'] = $username;
                exit;
            }
        }
    }
}
