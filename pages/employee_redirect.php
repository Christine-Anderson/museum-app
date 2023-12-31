<!--
    Employee redirect page following employee login
    redirects to appropriate view based on employee type
-->

<?php
    
    if (isset($_POST["login-button"])) { 
        userRedirect();
    } else { 
        invalidUserError();
    }

    function userRedirect() {
        $input_value =  $_POST["employee-type"];
        if ($input_value == "curator") { 
            header("Location: curator_search_article_exhibit_collection.php");
            exit;
        } else if ($input_value == "archivist") {
            header("Location: archivist_search_article.php");
            exit;
        } else {
            invalidUserError();
        }
    }

    function invalidUserError() { 
        echo "Invalid employee error"; 
        header("Location: employee_login.php");
        exit;
    }
?> 