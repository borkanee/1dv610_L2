<?php

namespace Controller;

require_once 'view/Message.php';

class LoginController
{
    private $sessionModel;
    private $cookieDAL;
    private $message;
    private $loginView;

    private $isLoggedIn = false;
    private $keepLoggedIn = false;
    private $userWantsToLogin = false;

    public function __construct(\model\SessionModel $sessionModel, \model\CookieDAL $cookieDAL, \view\LoginView $loginView, \view\Message $message)
    {
        $this->sessionModel = $sessionModel;
        $this->loginView = $loginView;
        $this->cookieDAL = $cookieDAL;
        $this->message = $message;
    }

    public function manageLogin()
    {
        $this->isLoggedIn = $this->sessionModel->isLoggedIn();
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
        $cookie = $this->loginView->getCookie();
        $cookieIsValid = $this->cookieDAL->cookieIsValid($cookie);

        if ($cookieIsValid) {
            $this->sessionModel->setLoggedIn($cookie->getName());
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
            if ($this->sessionModel->validCredentials($userCredentials)) {
                $this->sessionModel->setLoggedIn($userCredentials->getName());
                $this->loginView->setMessage($this->message->welcome());
                $this->isLoggedIn = true;

                if ($this->keepLoggedIn) {
                    $cookie = $this->loginView->createCookie();
                    $this->cookieDAL->storeCookie($cookie);
                    $this->loginView->setCookies($cookie);
                    $this->loginView->setMessage($this->message->welcomeRemembered());
                }
            } else {
                $this->loginView->setMessage($this->message->wrongCredentials());
            }
        }
    }

    private function doLogout()
    {
        $this->cookieDAL->removeCookie($this->sessionModel->getSessionUser());
        $this->loginView->clearCookies();
        $this->loginView->setMessage($this->message->bye());
        $this->sessionModel->setLoggedOut();
        $this->isLoggedIn = false;
    }
}
