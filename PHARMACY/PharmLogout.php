<?php
	include "config.php";
	session_start();
	unset($_SESSION["e_id"]);
	 if(session_destroy()) {
	header("Location:pharmLogin.php");
	}
?>