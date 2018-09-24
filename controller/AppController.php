<?php

namespace Controller;

require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

class AppController
{
    private $loginView;
    private $dateTimeView;
    private $layoutView;
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = new \Model\Login();
        $this->loginView = new \View\LoginView($this->loginModel);
        $this->dateTimeView = new \View\DateTimeView();
        $this->layoutView = new \View\LayoutView($this->loginModel);
    }

    public function start()
    {
        //TODO: MOVE ALL CODE TO OWN FUNCTIONS FOR EASIER READ > TO MANY IF-STATEMENTS....
        if ($this->loginView->cookiesAreSet() && $this->loginModel->isLoggedIn() === false) {
            $cookieName = $this->loginView->getCookieName();
            $cookiePassword = $this->loginView->getCookiePassword();
            $date = time();
            $cookiesAreValid = $this->loginModel->cookiesAreValid($cookieName, $cookiePassword, $date);
            if ($cookiesAreValid) {
                $this->loginModel->setLoggedIn();
                $this->loginView->welcomeBackWithCookie = true;
            } else {
                $this->loginView->wrongCookie = true;
                $this->loginView->clearCookies();
            }
        }

        if ($this->loginView->userWantsToLogin() && $this->loginModel->isLoggedIn() === false) {
            $name = $this->loginView->getUserName();
            $password = $this->loginView->getUserPassword();

            if ($this->loginModel->userExists($name, $password)) {
                $this->loginModel->setLoggedIn();
                if ($this->loginView->keepLoggedIn()) {
                    $cookieName = $name;
                    $cookiePassword = $this->loginView->generateRandomToken();
                    $expiryDate = time() + 60;
                    $this->loginModel->storeCookies($cookieName, $cookiePassword, $expiryDate);
                    $this->loginView->setCookies($cookiePassword, $expiryDate);
                }
            }
        } else if ($this->loginView->userWantsToLogin() && $this->loginModel->isLoggedIn() && $this->loginView->keepLoggedIn()) {
            $this->loginView->cookieMsgPresented = true;
        } else if ($this->loginView->userWantsToLogin() && $this->loginModel->isLoggedIn()) {
            $this->loginView->loginMsgPresented = true;
        } else if ($this->loginView->userWantsToLogout()) {
            if ($this->loginModel->isLoggedIn() === false) {
                $this->loginView->logoutMsgPresented = true;
            } else {
                $this->loginModel->removeCookies($this->loginView->getCookieName());
                $this->loginView->clearCookies();
                $this->loginModel->setLoggedOut();
            }
        }

        return $this->layoutView->render($this->loginModel, $this->loginView, $this->dateTimeView);
    }
}
