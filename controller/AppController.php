<?php

namespace Controller;

class AppController
{
    private $loginController;
    private $registerController;

    public function __construct(LoginController $loginController, RegisterController $registerController, \View\LayoutView $layoutView)
    {
        $this->loginController = $loginController;
        $this->registerController = $registerController;
        $this->layoutView = $layoutView;
    }

    public function start()
    {
        $this->loginController->manageLogin();
        $this->registerController->manageRegistration();
        
        return $this->layoutView->render();
    }
}
