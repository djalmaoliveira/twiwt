<?
define("APP_ROOT", dirname(__FILE__));
require_once "app/class/app.php";

session_start();

App::routing();


?>