<!--
    Curator Exhibit Planning page
    Can add and remove articles from exhibits and view select stats on exhibits
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibit Planning</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'curator_sidebar.php'
    ?>

<div class="content">
        <h2>Exhibit Planning</h2>

        <h3>Add Article to Exhibit</h3>
        <form method="POST" id="add-article-to-exhibit-request" action="curator_exhibit_planning.php">
            <input type="hidden" id="add-article-to-exhibit-request" name="add-article-to-exhibit-request">
            exhibit ID: <input type="number" name="exhibit-id-display-article" min="1000" max="9999" required>
            <br/><br/>
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
            <input type="submit" value="Add Article" name="submit-add-article-to-exhibit"></p>
            <br/>
        </form>

        <h3>Remove Article from Exhibit</h3>
        <form method="GET" id="remove-article-from-exhibit-request" action="curator_exhibit_planning.php">
            <input type="hidden" id="remove-article-from-exhibit-request" name="remove-article-from-exhibit-request">
            exhibit ID: <input type="number" name="exhibit-id" min="1000" max="9999" required>
            <br/><br/>
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
            storage location: <input type="text" name="storage-location" required>
            <br/><br/>
            <input type="submit" value="Remove" name="submit-remove-article-from-exhibit"></p>
            </br>
        </form>

        <h3>Visitors Per Exhibit</h3>
        <form method="GET" id="count-visitors-per-exhibit-request" action="curator_exhibit_planning.php">
            <input type="hidden" id="count-visitors-per-exhibit-request" name="count-visitors-per-exhibit-request">
            Number of visitors per exhibit
            <input type="submit" value="Count" name="submit-count-visitors-per-exhibit"></p>
            <br/>
        </form>
    
        <h3>Revenue From Exhibits</h3>
        <form method="GET" id="find-revenue-per-exhibit-request" action="curator_exhibit_planning.php">
            <input type="hidden" id="find-revenue-per-exhibit-request" name="find-revenue-per-exhibit-request">
            With at least <input type="number" name="num-visitors-per-exhibit" min="1" max="9999" required> visitor(s)
            <br/><br/>
            <input type="submit" value="Calculate" name="submit-find-revenue-per-exhibit"></p>
            <br/><br/>
        </form>

        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-add-article-to-exhibit', $request_method)) {
                    handleAddArticleToExhibitRequest();
                } else if (array_key_exists('submit-remove-article-from-exhibit', $request_method)) {
                    handleDeleteArticleFromExhibitRequest();
                } else if (array_key_exists('confirm-delete-request', $request_method)) {
                    confirmDeleteRequest();
                } else if (array_key_exists('reject-delete-request', $request_method)) {
                    rejectDeleteRequest();
                } else if (array_key_exists('submit-count-visitors-per-exhibit', $request_method)) {
                    handleCountVisitorsPerExhibitRequest();
                } else if  (array_key_exists('submit-find-revenue-per-exhibit', $request_method)) {
                    handleFindRevenuePerExhibitRequest();
                }
                disconnectFromDB(); 
            }
        }

        function handleAddArticleToExhibitRequest() {
            global $db_conn; 

            $exhibit_id = $_POST['exhibit-id-display-article'];
            $article_id = $_POST['article-id'];

            // if article does not already exist in exhibit, insert it
            $insert_result = executePlainSQL(
                "MERGE INTO displays target
                USING (SELECT " . $exhibit_id . " AS eoi, " . $article_id . " AS aoi FROM dual) source
                ON (target.exhibit_id = source.eoi AND target.article_id = source.aoi)
                WHEN NOT MATCHED THEN
                    INSERT (exhibit_id, article_id)
                    VALUES (source.eoi, source.aoi)");

            $rows = oci_num_rows($insert_result);
            // if a row was inserted, update article location to on display
            if ($rows > 0) {
                executePlainSQL(
                    "UPDATE article
                    SET storage_location = 'on display'
                    WHERE article_id = " . $article_id);
                
                echo '<p>The following article has been put on display:</p>';
                printArticleOnDisplay($article_id, $exhibit_id);
            } else {
                echo '<p>Article ' . $article_id . ' is already on display in exhibit ' . $exhibit_id . '.</p>';
            }

            oci_commit($db_conn);            
        }

        function handleDeleteArticleFromExhibitRequest() {
            global $db_conn;

            $article_id = $_GET['article-id'];
            $exhibit_id = $_GET['exhibit-id'];
            $storage_location = $_GET['storage-location'];

            echo '<p>Are you sure you want to remove the following article from the exhibit?</p>';
            printArticleOnDisplay($article_id, $exhibit_id);

            $delete_stmt =
                "DELETE 
                FROM displays
                WHERE
                    article_id = " . $article_id . " AND 
                    exhibit_id = " . $exhibit_id;
            
            $update_stmt = 
                "UPDATE article
                SET storage_location = '" . $storage_location . "'
                WHERE article_id = " . $article_id;

            confirmDeleteForm($delete_stmt, $update_stmt);
        }

        function handleCountVisitorsPerExhibitRequest() {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT e.exhibit_id, COUNT(*) AS number_of_visitors
                FROM exhibit e, admits a, ticket t
                WHERE e.exhibit_id = a.exhibit_id AND a.ticket_id = t.ticket_id
                GROUP BY e.exhibit_id
                ORDER BY e.exhibit_id");

            echo '<p>The number of visitors who have visited each exhibit is:</p>';
            printResults($result);
        }

        function handleFindRevenuePerExhibitRequest() {
            global $db_conn;

            $num_visitors = $_GET['num-visitors-per-exhibit'];

            $result = executePlainSQL(
                "SELECT e.exhibit_id, SUM(tp.price) AS total_revenue
                FROM ticket t, ticketprice tp, visitor v, admits a, exhibit e
                WHERE
                    t.ticket_type = tp.ticket_type AND
                    t.visitor_id = v.visitor_id AND
                    t.ticket_id = a.ticket_id AND
                    a.exhibit_id = e.exhibit_id
                GROUP BY e.exhibit_id
                HAVING " . $num_visitors . " <= (
                    SELECT COUNT(*)
                    FROM ticket t1, visitor v1, admits a1, exhibit e1
                    WHERE
                        t1.visitor_id = v1.visitor_id AND
                        t1.ticket_id = a1.ticket_id AND
                        a1.exhibit_id = e1.exhibit_id AND
                        e1.exhibit_id = e.exhibit_id)");

            echo '<p>The total revenue per exhibit with at least ' . $num_visitors . ' visitors is:</p>';
            printResults($result, ["Exhibit ID", "Total Revenue"]);
        }

        function confirmDeleteRequest() {
            global $db_conn; 

            $delete_stmt = $_POST['delete-stmt'];
            $update_stmt = $_POST['update-stmt'];

            executePlainSQL($delete_stmt);
            executePlainSQL($update_stmt);

            oci_commit($db_conn);
            echo '<br/><br/> <p>Article removed successfully.</p>';
        }

        function rejectDeleteRequest() {
            global $db_conn;
            echo '<p>Remove cancelled.</p>';
        }

        function confirmDeleteForm($delete_stmt, $update_stmt) {
            echo '<br/><br/>';
            echo '<form method="POST" id="archivist-update-artwork">';
            echo '<input type="hidden" name="delete-stmt" value="' . $delete_stmt . '">';
            echo '<input type="hidden" name="update-stmt" value="' . $update_stmt . '">';
            echo '<input type="submit" value="Yes" name="confirm-delete-request">';
            echo '&nbsp;&nbsp;';
            echo '<input type="submit" value="No" name="reject-delete-request"></p>';
            echo '</form>';
        } 

        function printArticleOnDisplay($article_id, $exhibit_id) {
            $result = executePlainSQL(
                "SELECT 
                    e.exhibit_id, e.exhibit_name,
                    a.article_id, a.article_name, a.storage_location
                FROM article a, displays d, exhibit e
                WHERE 
                    a.article_id = " . $article_id . " AND
                    a.article_id = d.article_id AND
                    d.exhibit_id = e.exhibit_id AND
                    e.exhibit_id = " . $exhibit_id);

            printResults($result);
        }

        // process database requests
        if (isset($_POST['submit-add-article-to-exhibit']) || isset($_POST['confirm-delete-request']) || isset($_POST['reject-delete-request'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['submit-remove-article-from-exhibit']) || isset($_GET['submit-count-visitors-per-exhibit']) || isset($_GET['submit-find-revenue-per-exhibit'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>
    
        <?php
        //     // removal statement 
        //     $removed = executePlainSQL(
        //     "SELECT article_id, article_name, storage_location
        //     FROM article
        //     WHERE article_id = " . $article_id);

        //     echo '<br/><br/>';
        //     echo 'The following article has been placed back into storage:';
        //     printResults($removed);
            
        // }

        // // process database requests
        // if (isset($_POST['submit-display-article']) || isset($_POST['submit-remove-article'])) {
        //     handleDatabaseRequest($_POST);
        // } 
        ?>