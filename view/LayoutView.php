<?php

namespace View;

class LayoutView
{
    private $loginModel;
    private $loginView;
    private $dateView;
    private $registerView;

    public function __construct(\model\Login $loginModel, LoginView $loginView, DateTimeView $dateView, RegisterView $registerView)
    {
        $this->loginModel = $loginModel;
        $this->loginView = $loginView;
        $this->dateView = $dateView;
        $this->registerView = $registerView;
    }

    public function render()
    {
        $navLink = '<a href="?register">Register a new user</a>';
        $pageToRender = $this->loginView->response();

        if (isset($_GET["register"])) {
            $navLink = '<a href="?">Back to login</a>';
            $pageToRender = $this->registerView->response();
            unset($_GET["register"]);

        }
        if ($this->loginModel->isLoggedIn()) {
            $navLink = null;
        }

        echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $navLink . '
          ' . $this->renderIsLoggedIn() . '

          <div class="container">
              ' . $pageToRender . '

              ' . $this->dateView->show() . '
          </div>
         </body>
      </html>
    ';
    }

    private function renderIsLoggedIn()
    {
        if ($this->loginModel->isLoggedIn()) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }
}
