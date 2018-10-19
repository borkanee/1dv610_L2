<?php

namespace View;

require_once 'model/UserCredentials.php';
require_once 'model/Cookie.php';

class LoginView
{
    private static $login = 'LoginView::Login';
    private static $logout = 'LoginView::Logout';
    private static $name = 'LoginView::UserName';
    private static $password = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';

    private static $userQuery = 'user';
    private $message = "";

    private $sessionModel;

    public function __construct(\Model\SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel;
    }

    public function response()
    {
        if (isset($_GET[self::$userQuery])) {
            $this->message = "Registered new user.";
            $_POST[self::$name] = $_GET[self::$userQuery];
        }

        $response = $this->sessionModel->isLoggedIn() ?
        $this->generateLogoutButtonHTML() :
        $response = $this->generateLoginFormHTML();

        return $response;
    }

    public function userWantsToLogin(): bool
    {
        return isset($_POST[self::$login]);
    }

    public function userWantsToLogout(): bool
    {
        return isset($_POST[self::$logout]);
    }

    public function keepLoggedIn(): bool
    {
        return isset($_POST[self::$keep]);
    }

    public function getUserCredentials()
    {
        try {
            return new \Model\UserCredentials($this->getUserName(), $this->getUserPassword());
        } catch (\Model\NameAndPasswordMissingException $e) {
            $this->message = 'Username is missing';
        } catch (\Model\PasswordMissingException $e) {
            $this->message = 'Password is missing';
        } catch (\Model\UserNameMissingException $e) {
            $this->message = 'Username is missing';
        }
    }

    public function getCookie()
    {
        return new \Model\Cookie($this->getCookieName(), $this->getCookiePassword(), time());
    }

    public function createCookie()
    {
        return new \Model\Cookie($this->sessionModel->getSessionUser(), $this->generateRandomToken(), time() + 3600); // Cookie only valid for 1 hour.
    }

    public function setCookies(\model\Cookie $cookie)
    {
        setcookie(self::$cookieName, $cookie->getName(), $cookie->getDate());
        setcookie(self::$cookiePassword, $cookie->getPassword(), $cookie->getDate());
    }

    public function clearCookies()
    {
        if ($this->cookiesAreSet()) {
            setcookie(self::$cookieName, '', time() - 3600);
            setcookie(self::$cookiePassword, '', time() - 3600);
        }
    }

    public function cookiesAreSet(): bool
    {
        return isset($_COOKIE[self::$cookieName]) &&
        isset($_COOKIE[self::$cookiePassword]);
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    private function getUserName()
    {
        if (isset($_POST[self::$name])) {
            return $_POST[self::$name];
        }
    }

    private function getUserPassword()
    {
        if (isset($_POST[self::$password])) {
            return $_POST[self::$password];
        }
    }

    private function getCookiePassword()
    {
        if (isset($_COOKIE[self::$cookiePassword])) {
            return $_COOKIE[self::$cookiePassword];
        }
    }

    private function getCookieName()
    {
        if (isset($_COOKIE[self::$cookieName])) {
            return $_COOKIE[self::$cookieName];
        }

    }

    private function generateRandomToken()
    {
        $strLength = 30;

        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $strLength);
    }

    private function generateLogoutButtonHTML()
    {
        return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $this->message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
    }

    private function generateLoginFormHTML()
    {
        return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $this->message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
    }
}
