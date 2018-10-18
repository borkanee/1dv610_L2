<?php

namespace Controller;

class LoginController
{
    private $loginModel;
    private $cookieModel;

    private $loginView;

    private $isLoggedIn = false;
    private $keepLoggedIn = false;
    private $userWantsToLogin = false;

    public function __construct(\model\Login $loginModel, \model\Cookies $cookieModel, \view\LoginView $loginView)
    {
        $this->loginModel = $loginModel;
        $this->loginView = $loginView;
        $this->cookieModel = $cookieModel;
    }

    public function manageLogin(): void
    {
        $this->isLoggedIn = $this->loginModel->isLoggedIn();
        $this->keepLoggedIn = $this->loginView->keepLoggedIn();
        $this->userWantsToLogin = $this->loginView->userWantsToLogin();

        if ($this->loginView->cookiesAreSet() && $this->isLoggedIn === false) {
            $this->loginWithCookie();
        }
        if ($this->userWantsToLogin && $this->isLoggedIn === false) {
            $this->doLogin();
        } else if ($this->userWantsToLogin && $this->isLoggedIn && $this->keepLoggedIn) {
            $this->loginView->cookieMsgPresented = true;
        } else if ($this->userWantsToLogin && $this->isLoggedIn) {
            $this->loginView->loginMsgPresented = true;
        } else if ($this->loginView->userWantsToLogout()) {
            $this->doLogout();
        }
    }

    private function loginWithCookie()
    {
        $cookiesAreValid = $this->cookieModel->cookiesAreValid($this->loginView->getCookieName(), $this->loginView->getCookiePassword(), time());

        if ($cookiesAreValid) {
            $this->loginModel->setLoggedIn($this->loginView->getCookieName());
            $this->isLoggedIn = true;
            $this->loginView->welcomeBackWithCookie = true;
        } else {
            $this->loginView->wrongCookie = true;
            $this->loginView->clearCookies();
        }
    }

    private function doLogin()
    {
        $name = $this->loginView->getUserName();
        $password = $this->loginView->getUserPassword();

        if ($this->loginModel->userExists($name, $password)) {
            $this->loginModel->setLoggedIn($name);
            $this->isLoggedIn = true;
            if ($this->keepLoggedIn) {
                $cookieName = $name;
                $cookiePassword = $this->loginView->generateRandomToken();
                $expiryDate = time() + 60;
                $this->cookieModel->storeCookies($cookieName, $cookiePassword, $expiryDate);
                $this->loginView->setCookies($cookiePassword, $expiryDate);
            }
        }
    }

    private function doLogout()
    {
        if ($this->isLoggedIn === false) {
            $this->loginView->logoutMsgPresented = true;
        } else {
            $this->cookieModel->removeCookies($this->loginView->getCookieName());
            $this->loginView->clearCookies();
            $this->loginModel->setLoggedOut();
            $this->isLoggedIn = false;
        }
    }
}
