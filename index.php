<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once 'model/Cookies.php';
require_once 'model/Login.php';
require_once 'model/Register.php';
require_once 'model/Snippets.php';

require_once 'controller/AppController.php';
require_once 'controller/SnippetController.php';
require_once 'controller/LoginController.php';
require_once 'controller/RegisterController.php';

require_once 'view/SnippetView.php';
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
$snippetModel = new \Model\Snippets();

//CREATE OBJECTS OF THE VIEWS
$snippetView = new \View\SnippetView();
$loginView = new \View\LoginView($loginModel);
$dtv = new \View\DateTimeView();
$registerView = new \View\RegisterView($registerModel);
$layoutView = new \View\LayoutView($loginModel, $loginView, $dtv, $registerView, $snippetView);

//CREATE OBJECTS OF THE CONTROLLERS
$snippetController = new \Controller\SnippetController($snippetView, $loginModel, $snippetModel);
$loginController = new \Controller\LoginController($loginModel, $cookieModel, $loginView);
$registerController = new \Controller\RegisterController($registerModel, $registerView);
$appController = new \Controller\AppController($loginController, $registerController, $snippetController, $layoutView);

// START
$appController->start();
