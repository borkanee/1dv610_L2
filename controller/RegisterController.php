<?php

namespace Controller;

class RegisterController
{
    private $registerDAL;
    private $registerView;
    private $message;

    public function __construct(\model\RegisterDAL $registerDAL, \view\RegisterView $registerView, \view\Message $message)
    {
        $this->registerDAL = $registerDAL;
        $this->registerView = $registerView;
        $this->message = $message;
    }

    public function manageRegistration()
    {
        if ($this->registerView->userWantsToRegister()) {
            $this->doRegistration();
        }
    }

    private function doRegistration()
    {
        $newUser = $this->registerView->getNewUser();

        if ($newUser) {
            if ($this->registerDAL->userExists($newUser)) {
                $this->registerView->setMessage($this->message->userExists());
            } else {
                $this->registerDAL->storeUser($newUser);
                $this->registerView->setRedirect($newUser->getName());
            }
        }
    }
}
