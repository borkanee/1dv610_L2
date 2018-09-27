<?php

namespace Controller;

require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';
require_once 'view/RegisterView.php';

class AppController
{
    private $loginView;
    private $dateTimeView;
    private $layoutView;
    private $loginModel;
    private $registerView;

    public function __construct()
    {
        $this->loginModel = new \Model\Login();
        $this->loginView = new \View\LoginView($this->loginModel);
        $this->dateTimeView = new \View\DateTimeView();
        $this->layoutView = new \View\LayoutView($this->loginModel);
        $this->registerView = new \View\RegisterView($this->loginModel);
    }

    public function start()
    {
        //TODO: MOVE ALL CODE TO OWN FUNCTIONS FOR EASIER READ > TOO MANY IF-STATEMENTS....
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
        if ($this->registerView->userWantsToRegister()) {
            $username = $this->registerView->getUserName();
            $password = $this->registerView->getUserPassword();
            $passwordRepeat = $this->registerView->getUserPasswordRepeat();
            $successfulRegistration = $this->loginModel->storeUser($username, $password, $passwordRepeat);
            if ($successfulRegistration) {
                unset($_GET["register"]);
                header("Location: http://39cbe94e.ngrok.io/1dv610_L2/");
                $_SESSION['registeredUser'] = $username;
                exit;
            }

        }

        return $this->layoutView->render($this->loginModel, $this->loginView, $this->dateTimeView, $this->registerView);
    }
}
