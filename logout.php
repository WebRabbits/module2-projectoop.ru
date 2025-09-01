<?php 
require_once(__DIR__ . "/init.php");

$user = new User();
$user->logout();
Cookie::delete(Config::get("cookie.cookie_name"));
Redirect::to("/");

?>