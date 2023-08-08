<!--
    Curator Exhibit and Collection page
    Can view exhibits and collections based on curator ID.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibit and Collection Info</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'curator_sidebar.php'
    ?>

    <div class="content">
        <h2>Exhibit and Collection Info</h2>
        <form method="GET" id="curator-exhibit-info-request" action="curator_exhibit_collection_info.php">
            <input type="hidden" id="curator-exhibit-info-request" name="curator-exhibit-info-request">
            <input type="submit" value="All Exhibits" name="submit-exhibit-info"></p>
        </form>
        <form method="GET" id="curator-collection-info-request" action="curator_exhibit_collection_info.php">
            <input type="hidden" id="curator-collection-info-request" name="curator-collection-info-request">
            <input type="submit" value="All Collections" name="submit-collection-info"></p>
        </form>
        <hr />
        <h2> Connected Works by Curator</h2>
        <form method="GET" id="curator-work-request" action="curator_exhibit_collection_info.php">
            <input type="hidden" id="curator-work-request" name="curator-work-request">
            curator SIN: <input type="number" name="curator-sin" min="100000000" max="999999999" required>
            <input type="submit" value="Find Connected Works" name="submit-curator-work"></p>
        </form>
        <hr />
        
        
        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';
        

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-exhibit-info', $request_method)) {
                    handleExhibitInfoRequest();
                } else if (array_key_exists('submit-collection-info', $request_method)) {
                    handleCollectionInfoRequest();
                } else if (array_key_exists('submit-curator-work', $request_method)) {
                    handleCuratorWorkRequest();
                }
                disconnectFromDB();
            }
        }

        function handleExhibitInfoRequest() {
            global $db_conn;

            $result = executePlainSQL(
            "SELECT e.exhibit_id, e.exhibit_name, a.article_id, a. article_name ,e.start_date, e.end_date, e.sin
            FROM exhibit e, article a, displays d
            WHERE e.exhibit_id = d.exhibit_id AND a.article_id = d.article_id
            ORDER BY e.exhibit_id");

            echo '<br/><br/>';
            echo 'All Exhibits:';
            printResults($result);
        }

        function handleCollectionInfoRequest() {
            global $db_conn;

            $result = executePlainSQL(
            "SELECT c.collection_id, c.name, a.article_id, a. article_name , c.sin
            FROM collection c, article a, contains cont
            WHERE c.collection_id = cont.collection_id
            AND a.article_id = cont.article_id
            ORDER BY c.collection_id");

            echo '<br/><br/>';
            echo 'All Collections:';
            printResults($result);
        }

        function handleCuratorWorkRequest() {
            global $db_conn;

            $curator_sin = $_GET['curator-sin'];

            $result = executePlainSQL(
            "SELECT DISTINCT c.collection_id, c.name, e.exhibit_id, e.exhibit_name, a.article_name, c.sin
            FROM collection c, exhibit e, article a, displays d, contains cont
            WHERE c.sin = " . $curator_sin . " AND 
            a.article_id = d.article_id AND
            a.article_id = cont.article_id");

            echo '<br/><br/>';
            echo 'All Exhibits and Collections by ' . $curator_sin . ':';
            printResults($result);
        }

        if (isset($_GET['submit-exhibit-info']) || isset($_GET['submit-collection-info']) || isset($_GET['submit-curator-work'])) {
            handleDatabaseRequest($_GET);
        }


        ?>
    </div> 
</body>
</html>