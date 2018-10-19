<?php

namespace View;

class LayoutView
{
    private static $registerLink = '<a href="?register">Register a new user</a>';
    private static $loginLink = '<a href="?">Back to login</a>';
    private static $snippetsLink = '<a href="?snippets">My Snippets</a>';

    private static $regiserQuery = 'register';
    private static $snippetsQuery = 'snippets';

    private $loginModel;
    private $loginView;
    private $dateView;
    private $registerView;
    private $snippetView;

    public function __construct(\model\Login $loginModel, LoginView $loginView, DateTimeView $dateView, RegisterView $registerView, SnippetView $snippetView)
    {
        $this->loginModel = $loginModel;
        $this->loginView = $loginView;
        $this->dateView = $dateView;
        $this->registerView = $registerView;
        $this->snippetView = $snippetView;
    }

    public function render()
    {
        $navLink = self::$registerLink;
        $pageToRender = $this->loginView->response();

        if ($this->loginModel->isLoggedIn()) {
            $navLink = self::$snippetsLink;
        }

        if (isset($_GET[self::$regiserQuery])) {
            $navLink = self::$loginLink;
            $pageToRender = $this->registerView->response();
            unset($_GET[self::$regiserQuery]);

        }
        if (isset($_GET[self::$snippetsQuery])) {
            $navLink = self::$loginLink;
            $pageToRender = $this->snippetView->response();
            unset($_GET[self::$snippetsQuery]);
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
