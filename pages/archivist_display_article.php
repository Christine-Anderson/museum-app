<!--
    Archivist display article page
    Archivist can put articles on display in an exhibit
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AArchivist display article page</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'archivist_sidebar.php'
    ?>

    <div class="content">
        <h2>Display Article in Exhibit</h2>
        <form method="POST" id="display-article-request" action="archivist_display_article.php">
            <input type="hidden" id="display-article-request" name="display-article-request">
            exhibit ID: <input type="number" name="exhibit-id" min="1000" max="9999" required>
            <br/><br/>
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
            <input type="submit" value="Display Article" name="submit-display-article"></p>
        </form>

        <form method="GET" id="archivist-view-exhibit-request" action="archivist_display_article.php">
            <input type="hidden" id="archivist-view-exhibit-request" name="archivist-view-exhibit-request">
            <input type="submit" value="View Exhibits" name="submit-view-exhibit"></p>
        </form>
    
        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-display-article', $request_method)) {
                    handleDisplayArticleRequest();
                } else if (array_key_exists('submit-view-exhibit', $request_method)) {
                    handleViewExhibitsRequest();
                }
                disconnectFromDB(); 
            }
        }

        function handleDisplayArticleRequest() {
            global $db_conn; 

            $exhibit_id = $_POST['exhibit-id'];
            $article_id = $_POST['article-id'];

            $tuple = array (
                ":exhibit_id" => $exhibit_id,
                ":article_id" => $article_id
            );

            $all_tuples = array (
                $tuple
            );

            // put article on display in exhibit
            executeBoundSQL(
                "INSERT INTO displays(exhibit_id, article_id)
                VALUES (:exhibit_id, :article_id)", $all_tuples);
            
            // update article location to on display
            executePlainSQL(
                "UPDATE article
                SET storage_location = 'on display'
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            // generate output 
            $result = executePlainSQL(
                "SELECT a.article_id, a.article_name, a.storage_location,
                        e.exhibit_id, e.exhibit_name
                FROM article a, displays d, exhibit e
                WHERE 
                    a.article_id = " . $article_id . " AND
                    a.article_id = d.article_id AND
                    d.exhibit_id = e.exhibit_id AND
                    e.exhibit_id = " . $exhibit_id);

            echo '<br/><br/>';
            echo 'The following has been put on display:';
            printResults($result);
        }

        function handleViewExhibitsRequest() {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT exhibit_id, exhibit_name, start_date, end_date
                FROM exhibit");

            echo '<br/><br/>';
            printResults($result);
        }

        // process database requests
        if (isset($_POST['submit-display-article'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['submit-view-exhibit'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>
