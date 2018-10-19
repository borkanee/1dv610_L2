<?php

namespace View;

require_once 'model/NewUser.php';

class RegisterView
{
    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';

    private static $redirectLocation = 'http://104.248.17.234/?user=';

    private $message = "";

    public function response()
    {
        return $this->generateRegisterFormHTML();
    }

    public function userWantsToRegister(): bool
    {
        return isset($_POST[self::$register]);
    }

    public function getNewUser()
    {
        try {
            return new \Model\NewUser($this->getUserName(), $this->getUserPassword(), $this->getUserPasswordRepeat());
        } catch (\Model\EmptyRegistrationException $e) {
            $this->message = 'Username has too few characters, at least 3 characters. Password has too few characters, at least 6 characters.';
        } catch (\Model\InvalidCharactersException $e) {
            $this->message = 'Username contains invalid characters.';
            $_POST[self::$name] = strip_tags($_POST[self::$name]);
        } catch (\Model\UserNameShortException $e) {
            $this->message = 'Username has too few characters, at least 3 characters.';
        } catch (\Model\PasswordShortException $e) {
            $this->message = 'Password has too few characters, at least 6 characters.';
        } catch (\Model\PasswordsNotMatchException $e) {
            $this->message = 'Passwords do not match.';
        }
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setRedirect($username)
    {
        header('Location: ' . self::$redirectLocation . $username . '');
        exit;
    }

    private function generateRegisterFormHTML()
    {
        return '
			<form method="post">
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$messageId . '">' . $this->message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$password . '">RepeatPassword :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" />

					<input type="submit" name="' . self::$register . '" value="register" />
				</fieldset>
			</form>
		';
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

    private function getUserPasswordRepeat()
    {
        if (isset($_POST[self::$passwordRepeat])) {
            return $_POST[self::$passwordRepeat];
        }
    }
}
