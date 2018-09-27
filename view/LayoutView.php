<?php

namespace View;

class LayoutView
{

    public function render(\model\Login $loginModel, LoginView $loginView, DateTimeView $dateView, RegisterView $registerView)
    {
        $navLink = '<a href="?register">Register a new user</a>';
        $pageToRender = $loginView->response();

        if (isset($_GET["register"])) {
            $navLink = '<a href="?">Back to login</a>';
            $pageToRender = $registerView->response();
            unset($_GET["register"]);

        }
        if ($loginModel->isLoggedIn()) {
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
          ' . $this->renderIsLoggedIn($loginModel) . '

          <div class="container">
              ' . $pageToRender . '

              ' . $dateView->show() . '
          </div>
         </body>
      </html>
    ';
    }

    private function renderIsLoggedIn($loginModel)
    {
        if ($loginModel->isLoggedIn()) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }
}
