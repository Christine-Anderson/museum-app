<!-- 
	Wrapper for debugging PHP
	Access link: https://www.students.cs.ubc.ca/~tineand/museum_app/public/debug_wrapper.php
	include php pages you wish to debug
-->

<?php
	error_reporting(-1);
	ini_set('display_errors',1);
	include("archivist_display_article.php");
    // include("archivist_examine_article.php");
	// include("archivist_search_article.php");
	// include("employee_login.php");
	// include("employee_redirect.php");
?>