<?php 
require_once(__DIR__ . "/init.php");

$user = new User();
$user->logout();

Redirect::to("/");

?>