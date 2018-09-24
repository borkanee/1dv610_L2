<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once('controller/AppController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
/*$v = new \View\LoginView();
$dtv = new \View\DateTimeView();
$lv = new \View\LayoutView();

$lv->render(false, $v, $dtv);

$storage = new \Model\UserStorage();
 */

$AppController = new \Controller\AppController();
$AppController->start();
