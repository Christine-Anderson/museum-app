<!--
    User redirect page following user login
	redirects to appropriate view based on user type
-->

<?php
    
	if (isset($_POST["login-button"])) { 
        userRedirect();
    } else { 
        invalidUserError();
	}

    function userRedirect() {
        $input_value =  $_POST["user-type"];
        if ($input_value == "visitor") { 
            header("Location: visitor_main.php");
            exit;
        } else if ($input_value == "owner") {
            header("Location: owner_main.php");
            exit;
        } else if ($input_value == "employee") {
            header("Location: employee_main.php");
            exit;
        } else {
            invalidUserError();
        }
    }

    function invalidUserError() { 
        echo "Invalid user error"; 
        header("Location: user_login.php");
        exit;
    }
?> 