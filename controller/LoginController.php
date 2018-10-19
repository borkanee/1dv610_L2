<?php

namespace Controller;

require_once 'view/Message.php';

class LoginController
{
    private $loginModel;
    private $cookieModel;
    private $message;
    private $loginView;

    private $isLoggedIn = false;
    private $keepLoggedIn = false;
    private $userWantsToLogin = false;

    public function __construct(\model\Login $loginModel, \model\Cookies $cookieModel, \view\LoginView $loginView)
    {
        $this->loginModel = $loginModel;
        $this->loginView = $loginView;
        $this->cookieModel = $cookieModel;
        $this->message = new \view\Message();
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
        } else if ($this->loginView->userWantsToLogout() && $this->isLoggedIn) {
            $this->doLogout();
        }
    }

    private function loginWithCookie()
    {
        $cookiesAreValid = $this->cookieModel->cookiesAreValid($this->loginView->getCookieName(), $this->loginView->getCookiePassword(), time());

        if ($cookiesAreValid) {
            $this->loginModel->setLoggedIn($this->loginView->getCookieName());
            $this->loginView->setMessage($this->message->welcomeBackCookie());
            $this->isLoggedIn = true;
        } else {
            $this->loginView->setMessage($this->message->wrongCookie());
            $this->loginView->clearCookies();
        }
    }

    private function doLogin()
    {
        $userCredentials = $this->loginView->getUserCredentials();

        if ($userCredentials) {
            if ($this->loginModel->userExists($userCredentials)) {
                $this->loginModel->setLoggedIn($userCredentials->getName());
                $this->loginView->setMessage($this->message->welcome());
                $this->isLoggedIn = true;
                
                if ($this->keepLoggedIn) {
                    $cookieName = $userCredentials->getName();
                    $cookiePassword = $this->loginView->generateRandomToken();
                    $expiryDate = time() + 60;
                    $this->cookieModel->storeCookies($cookieName, $cookiePassword, $expiryDate);
                    $this->loginView->setCookies($cookiePassword, $expiryDate);
                    $this->loginView->setMessage($this->message->welcomeRemembered());
                }
            } else {
                $this->loginView->setMessage($this->message->wrongCredentials());
            }
        }
    }

    private function doLogout()
    {
        $this->cookieModel->removeCookies($this->loginView->getCookieName());
        $this->loginView->clearCookies();
        $this->loginView->setMessage($this->message->bye());
        $this->loginModel->setLoggedOut();
        $this->isLoggedIn = false;
    }
}
