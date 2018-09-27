<?php

namespace View;

class RegisterView
{
    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';

    private $loginModel;

    public function __construct(\Model\Login $loginModel)
    {
        $this->loginModel = $loginModel;
    }

    /**
     * Create HTTP response
     *
     * Should be called after a register attempt has been determined
     *
     * @return  void BUT writes to standard output and cookies!
     */
    public function response()
    {
        $message = '';

        if ($this->inputIsSet()) {

            $username = $this->getUserName();
            $password = $this->getUserPassword();
            $passwordRepeat = $this->getUserPasswordRepeat();

            if (strlen($username) < 3) {
                $message = 'Username has too few characters, at least 3 characters. ';
            }
            if (strlen($password) < 6) {
                $message .= 'Password has too few characters, at least 6 characters. ';
            }
            if ($username && !$password) {
                $message = 'Password has too few characters, at least 6 characters. ';
            }
            if ($password != $passwordRepeat) {
                $message = 'Passwords do not match.';
            }
            if ($this->loginModel->userExistsReg($username)) {
                $message = 'User exists, pick another username.';
            }
        }
        $response = $this->generateRegisterFormHTML($message);

        return $response;
    }

    /**
     * Generate HTML code on the output buffer for the register form
     * @param $message, String output message
     * @return void, BUT writes to standard output!
     */
    private function generateRegisterFormHTML($message)
    {
        return '
			<form method="post" >
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

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

    private function inputIsSet()
    {
        return isset($_POST[self::$name]) && isset($_POST[self::$password]);
    }

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
    public function getUserPasswordRepeat()
    {
        if (isset($_POST[self::$passwordRepeat])) {
            return $_POST[self::$passwordRepeat];
        }
    }
}
