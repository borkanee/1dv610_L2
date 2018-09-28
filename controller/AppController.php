<?php

namespace Controller;

require_once 'model/Register.php';
require_once 'model/Cookies.php';
require_once 'model/Login.php';
require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';
require_once 'view/RegisterView.php';


class AppController
{
    private $loginModel;
    private $registerModel;
    private $loginView;
    private $dateTimeView;
    private $layoutView;
    private $registerView;
    private $cookieModel;

    public function __construct()
    {
        $this->loginModel = new \Model\Login();
        $this->registerModel = new \Model\Register();
        $this->dateTimeView = new \View\DateTimeView();
        $this->layoutView = new \View\LayoutView($this->loginModel);
        $this->loginView = new \View\LoginView($this->loginModel);
        $this->registerView = new \View\RegisterView($this->registerModel);
        $this->cookieModel = new \Model\Cookies();
    }

    public function start()
    {
        //TODO: Figure out a better way for the code below...too many if-statements??
        if ($this->loginView->cookiesAreSet() && $this->loginModel->isLoggedIn() === false) {
            $cookieName = $this->loginView->getCookieName();
            $cookiePassword = $this->loginView->getCookiePassword();
            $date = time();
            $cookiesAreValid = $this->cookieModel->cookiesAreValid($cookieName, $cookiePassword, $date);
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
                    $this->cookieModel->storeCookies($cookieName, $cookiePassword, $expiryDate);
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
                $this->cookieModel->removeCookies($this->loginView->getCookieName());
                $this->loginView->clearCookies();
                $this->loginModel->setLoggedOut();
            }
        }
        if ($this->registerView->userWantsToRegister()) {
            $username = $this->registerView->getUserName();
            $password = $this->registerView->getUserPassword();
            $passwordRepeat = $this->registerView->getUserPasswordRepeat();
            $successfulRegistration = $this->registerModel->storeUser($username, $password, $passwordRepeat);
            if ($successfulRegistration) {
                unset($_GET["register"]);
                header("Location: http://104.248.17.234/");
                $_SESSION['registeredUser'] = $username;
                exit;
            }
        }

        return $this->layoutView->render($this->loginModel, $this->loginView, $this->dateTimeView, $this->registerView);
    }
}
