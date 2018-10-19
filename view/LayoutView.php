<?php

namespace View;

class LayoutView
{
    private static $registerLink = '<a href="?register">Register a new user</a>';
    private static $loginLink = '<a href="?">Back to login</a>';
    private static $snippetsLink = '<a href="?snippets">My Snippets</a>';
    private static $regiserQuery = 'register';
    private static $snippetsQuery = 'snippets';

    private $sessionModel;
    private $loginView;
    private $dateView;
    private $registerView;
    private $snippetView;

    private $navLink;
    private $pageToRender;

    public function __construct(\model\SessionModel $sessionModel, LoginView $loginView, DateTimeView $dateView, RegisterView $registerView, SnippetView $snippetView)
    {
        $this->sessionModel = $sessionModel;
        $this->loginView = $loginView;
        $this->dateView = $dateView;
        $this->registerView = $registerView;
        $this->snippetView = $snippetView;
    }

    public function render()
    {
        $this->setNavAndPage();

        echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->navLink . '
          ' . $this->renderIsLoggedIn() . '

          <div class="container">
              ' . $this->pageToRender . '

              ' . $this->dateView->show() . '
          </div>
         </body>
      </html>
    ';
    }

    private function renderIsLoggedIn()
    {
        if ($this->sessionModel->isLoggedIn()) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }

    private function setNavAndPage()
    {
        $this->navLink = self::$registerLink;
        $this->pageToRender = $this->loginView->response();

        if ($this->sessionModel->isLoggedIn()) {
            $this->navLink = self::$snippetsLink;
        }

        if (isset($_GET[self::$regiserQuery])) {
            $this->navLink = self::$loginLink;
            $this->pageToRender = $this->registerView->response();
            unset($_GET[self::$regiserQuery]);

        }
        if (isset($_GET[self::$snippetsQuery])) {
            $this->navLink = self::$loginLink;
            $this->pageToRender = $this->snippetView->response();
            unset($_GET[self::$snippetsQuery]);
        }
    }
}
