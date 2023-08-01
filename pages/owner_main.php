<!--
    Title
    Description TODO
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner view main page</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <a class="active" href="#home">Home</a>
        <a href="#news">News</a>
        <a href="#contact">Contact</a>
        <a href="#about">About</a>
    </div>

    <div class="content">
        <h2>User Profile</h2>
        <p>Please enter your ownerID to view your user profile:</p>

        <form method="GET" action="owner_main.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            ownerID: <input type="text" name="ownerID"> <br /><br />
            <input type="submit" value="View" name="countTuples"></p>
        </form>

        <hr />

        <h2>Update User Information</h2>
        <p>instructions...</p>

        <form method="POST" action="oracle-test.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>
            
        <?php

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL;
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
//fix indentation from here on down
            function debugAlertMessage($message) {
                global $show_debug_alert_messages;

                if ($show_debug_alert_messages) {
                    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
                }
            }

            function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
                echo "<br>running ".$cmdstr."<br>"; //recomment
                global $db_conn, $success;

                $statement = OCIParse($db_conn, $cmdstr);

                if (!$statement) {
                    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                    echo htmlentities($e['message']);
                    $success = False;
                }

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = oci_error($statement); // For OCIExecute errors pass the statement handle
                    echo htmlentities($e['message']);
                    $success = False;
                }

                return $statement;
            }

            function executeBoundSQL($cmdstr, $list) {
                /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
            In this case you don't need to create the statement several times. Bound variables cause a statement to only be
            parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
            See the sample code below for how this function is used */

                global $db_conn, $success;
                $statement = OCIParse($db_conn, $cmdstr);

                if (!$statement) {
                    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($db_conn);
                    echo htmlentities($e['message']);
                    $success = False;
                }

                foreach ($list as $tuple) {
                    foreach ($tuple as $bind => $val) {
                        echo $val; //recomment
                        echo "<br>".$bind."<br>"; //recomment
                        OCIBindByName($statement, $bind, $val);
                        unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
                    }

                    $r = OCIExecute($statement, OCI_DEFAULT);
                    if (!$r) {
                        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                        $e = OCI_Error($statement); // For OCIExecute errors, pass the statement handle
                        echo htmlentities($e['message']);
                        echo "<br>";
                        $success = False;
                    }
                }
            }

            function printResult($result) { //prints results from a select statement
                echo "<br>Retrieved data from table demoTable:<br>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th></tr>";

                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
                }

                echo "</table>";
            }

            function connectToDB() {
                global $db_conn;

                // Your username is ora_(CWL_ID) and the password is a(student number). For example,
                // ora_platypus is the username and a12345678 is the password.
                $db_conn = OCILogon("ora_tineand", "a94290160", "dbhost.students.cs.ubc.ca:1522/stu");

                if ($db_conn) {
                    debugAlertMessage("Database is Connected");
                    return true;
                } else {
                    debugAlertMessage("Cannot connect to Database");
                    $e = OCI_Error(); // For OCILogon errors pass no handle
                    echo htmlentities($e['message']);
                    return false;
                }
            }

            function disconnectFromDB() {
                global $db_conn;

                debugAlertMessage("Disconnect from Database");
                OCILogoff($db_conn);
            }

            function handleUpdateRequest() {
                global $db_conn;

                $old_name = $_POST['oldName'];
                $new_name = $_POST['newName'];

                // you need the wrap the old name and new name values with single quotations
                executePlainSQL("UPDATE demoTable SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
                OCICommit($db_conn);
            }

            function handleInsertRequest() {
                global $db_conn;

                //Getting the values from user and insert data into the table
                $tuple = array (
                    ":bind1" => $_POST['insNo'],
                    ":bind2" => $_POST['insName']
                );

                $alltuples = array (
                    $tuple
                );

                executeBoundSQL("insert into demoTable values (:bind1, :bind2)", $alltuples);
                OCICommit($db_conn);
            }

            function handleCountRequest() {
                global $db_conn;

                $result = executePlainSQL("SELECT Count(*) FROM demoTable");

                if (($row = oci_fetch_row($result)) != false) {
                    echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
                }
            }

            // HANDLE ALL POST ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handlePOSTRequest() {
                if (connectToDB()) {
                    if (array_key_exists('resetTablesRequest', $_POST)) {
                        handleResetRequest();
                    } else if (array_key_exists('updateQueryRequest', $_POST)) {
                        handleUpdateRequest();
                    } else if (array_key_exists('insertQueryRequest', $_POST)) {
                        handleInsertRequest();
                    }

                    disconnectFromDB();
                }
            }

            // HANDLE ALL GET ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handleGETRequest() {
                if (connectToDB()) {
                    if (array_key_exists('countTuples', $_GET)) {
                        handleCountRequest();
                    }

                    disconnectFromDB();
                }
            }

            if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
                handlePOSTRequest();
            } else if (isset($_GET['countTupleRequest'])) {
                handleGETRequest();
            }
            ?>        
        </div>
</body>
</html>
