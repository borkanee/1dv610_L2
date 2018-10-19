<?php

namespace Controller;

class RegisterController
{
    private $registerModel;
    private $registerView;
    private $message;

    public function __construct(\model\Register $registerModel, \view\RegisterView $registerView, \view\Message $message)
    {
        $this->registerModel = $registerModel;
        $this->registerView = $registerView;
        $this->message = $message;
    }

    public function manageRegistration(): void
    {
        if ($this->registerView->userWantsToRegister()) {
            $this->doRegistration();
        }
    }

    private function doRegistration()
    {
        $newUser = $this->registerView->getNewUser();

        if ($newUser) {
            if ($this->registerModel->userExists($newUser)) {
                $this->registerView->setMessage($this->message->userExists());
            } else {
                $this->registerModel->storeUser($newUser);
                $this->registerView->setRedirect($newUser->getName());
            }
        }
    }
}
