<?php

namespace Controller;

class AppController
{
    private $loginController;
    private $registerController;
    private $snippetController;
    private $layoutView;

    public function __construct(LoginController $loginController, RegisterController $registerController, SnippetController $snippetController, \View\LayoutView $layoutView)
    {
        $this->loginController = $loginController;
        $this->registerController = $registerController;
        $this->layoutView = $layoutView;
        $this->snippetController = $snippetController;
    }

    public function start()
    {
        $this->loginController->manageLogin();
        $this->registerController->manageRegistration();
        $this->snippetController->manageSnippets();

        return $this->layoutView->render();
    }
}
