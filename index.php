<?php

if (!isset($_SESSION)) {
    session_start();
}


require_once 'model/Cookies.php';
require_once 'model/Login.php';
require_once 'model/Register.php';

require_once 'controller/AppController.php';
require_once 'controller/LoginController.php';
require_once 'controller/RegisterController.php';

require_once 'view/LoginView.php';
require_once 'view/RegisterView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


//CREATE OBJECTS OF MODELS
$loginModel = new \Model\Login();
$registerModel = new \Model\Register();
$cookieModel = new \Model\Cookies();

//CREATE OBJECTS OF THE VIEWS
$loginView = new \View\LoginView($loginModel);
$dtv = new \View\DateTimeView();
$registerView = new \View\RegisterView($registerModel);
$layoutView = new \View\LayoutView($loginModel, $loginView, $dtv, $registerView);

//CREATE OBJECTS OF THE CONTROLLERS
$loginController = new \Controller\LoginController($loginModel, $cookieModel, $loginView);
$registerController = new \Controller\RegisterController($registerModel, $registerView);
$appController = new \Controller\AppController($loginController, $registerController, $layoutView);

// START
$appController->start();
