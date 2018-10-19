<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once 'model/CookieDAL.php';
require_once 'model/SessionModel.php';
require_once 'model/RegisterDAL.php';
require_once 'model/SnippetDAL.php';
require_once 'model/Database.php';

require_once 'controller/AppController.php';
require_once 'controller/SnippetController.php';
require_once 'controller/LoginController.php';
require_once 'controller/RegisterController.php';

require_once 'view/SnippetView.php';
require_once 'view/LoginView.php';
require_once 'view/RegisterView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

//TURN ON WHEN TESTING LOCALLY
// error_reporting(E_ALL);
// ni_set('display_errors', 'On');

//CREATE OBJECTS OF MODELS
$database = new \Model\Database();
$sessionModel = new \Model\SessionModel($database);
$registerDAL = new \Model\RegisterDAL($database);
$cookieDAL = new \Model\CookieDAL($database);
$snippetDAL = new \Model\SnippetDAL($database);

//CREATE OBJECTS OF THE VIEWS
$message = new \View\Message();
$snippetView = new \View\SnippetView();
$loginView = new \View\LoginView($sessionModel);
$dtv = new \View\DateTimeView();
$registerView = new \View\RegisterView($registerDAL);
$layoutView = new \View\LayoutView($sessionModel, $loginView, $dtv, $registerView, $snippetView);

//CREATE OBJECTS OF THE CONTROLLERS
$snippetController = new \Controller\SnippetController($snippetView, $sessionModel, $snippetDAL);
$loginController = new \Controller\LoginController($sessionModel, $cookieDAL, $loginView, $message);
$registerController = new \Controller\RegisterController($registerDAL, $registerView, $message);
$appController = new \Controller\AppController($loginController, $registerController, $snippetController, $layoutView);

$appController->start();
