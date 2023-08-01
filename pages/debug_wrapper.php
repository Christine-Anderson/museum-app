<!-- 
	Wrapper for debugging PHP
	Access link: https://www.students.cs.ubc.ca/~tineand/museum_app/public/debug_wrapper.php
	include php pages you wish to debug
-->

<?php
	error_reporting(-1);
	ini_set('display_errors',1);
	include("user_login.php");
	include("visitor_main.php");
	include("owner_main.php");
	include("employee_main.php");
	include("user_redirect.php"); 
?>