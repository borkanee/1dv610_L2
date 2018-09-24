<?php

namespace View;

require_once 'model/Login.php';

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

    private $loginModel;

    public $logoutMsgPresented = false;
    public $loginMsgPresented = false;
    public $cookieMsgPresented = false;
    public $welcomeBackWithCookie = false;
    public $wrongCookie = false;

    public function __construct(\Model\Login $loginModel)
    {
        $this->loginModel = $loginModel;
    }

    // For testing

    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @return  void BUT writes to standard output and cookies!
     */
    public function response()
    {
        $message = '';

        if ($this->inputIsSet()) {

            $username = $this->getUserName();
            $password = $this->getUserPassword();

            if (!$username && !$password) {
                $message = 'Username is missing';
            } else if (!$password) {
                $message = 'Password is missing';
            } else if (!$username) {
                $message = 'Username is missing';
            } else if (!$this->loginModel->userExists($username, $password)) {
                $message = 'Wrong name or password';
            } else if ($this->loginModel->isLoggedIn() && !$this->keepLoggedIn() && !$this->loginMsgPresented) {
                $message = 'Welcome';
            } else if ($this->loginModel->isLoggedIn() && $this->keepLoggedIn() && !$this->cookieMsgPresented) {
                $message = 'Welcome and you will be remembered';
            } else {
                $message = '';
            }
        } else if ($this->userWantsToLogout() && !$this->logoutMsgPresented) {
            $message = 'Bye bye!';
        } else if ($this->cookiesAreSet() && $this->loginModel->isLoggedIn() && !$this->welcomeBackWithCookie) {
            $message = 'Welcome back with cookie';
        } else if ($this->wrongCookie) {
            $message = 'Wrong information in cookies';
        } else {
            $message = "";
        }

        $response = $this->loginModel->isLoggedIn() ?
        $this->generateLogoutButtonHTML($message) :
        $response = $this->generateLoginFormHTML($message);

        return $response;
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
     */
    private function generateLogoutButtonHTML($message)
    {
        return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
     */
    private function generateLoginFormHTML($message)
    {
        return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

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

    public function userWantsToLogin(): bool
    {
        if (isset($_POST[self::$login])) {
            return true;
        }
        return false;
    }

    public function userWantsToLogout(): bool
    {
        return isset($_POST[self::$logout]);
    }

    public function inputIsSet(): bool
    {
        return isset($_POST[self::$name]) && isset($_POST[self::$password]);
    }

    public function keepLoggedIn(): bool
    {
        if (isset($_POST[self::$keep])) {
            return true;
        } else {
            return false;
        }
    }

    public function setCookies($tempPassword, $expiryDate)
    {
        setcookie(self::$cookieName, $this->getUserName(), $expiryDate);
        setcookie(self::$cookiePassword, $tempPassword, $expiryDate);
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

    public function generateRandomToken()
    {
        $strLength = 30;

        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $strLength);
    }

    //CREATE GET-FUNCTIONS TO FETCH POST VARIABLES
    public function getUserName()
    {
        if (isset($_POST[self::$name])) {
            return $_POST[self::$name];
        }
    }
    public function getUserPassword()
    {
        if (isset($_POST[self::$password])) {
            return $_POST[self::$password];
        }
    }

    public function getCookiePassword()
    {
        if (isset($_COOKIE[self::$cookiePassword])) {
            return $_COOKIE[self::$cookiePassword];
        }
    }
    public function getCookieName()
    {
        if (isset($_COOKIE[self::$cookieName])) {
            return $_COOKIE[self::$cookieName];
        }

    }
}
