<?php 
session_start();
require_once(__DIR__ . "/classes/Session.php");

echo "Сообщение на странице reg_succ после успешной регистрации - " . Session::flash("success");

?>