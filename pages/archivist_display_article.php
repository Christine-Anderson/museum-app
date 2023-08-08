<!--
    Archivist display article page
    Archivist can search exhibits, find articles currently on display in an exhibit, and put articles on display
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivist display article</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'archivist_sidebar.php'
    ?>

    <div class="content">
        <h2>Articles on Display</h2>

        <h3>Search Exhibits</h3>
        <form method="GET" id="archivist-search-exhibit-request" action="archivist_display_article.php">
            <input type="hidden" id="archivist-search-exhibit-request" name="archivist-search-exhibit-request">
            <input type="text" name="search-term">
            <input type="submit" value="Search Exhibits" name="submit-search-exhibit"></p>
            <br/>
        </form>

        <h3>Current Articles on Display</h3>
        <form method="GET" id="find-article-on-display-request" action="archivist_display_article.php">
            <input type="hidden" id="find-article-on-display-request" name="find-article-on-display-request">
            exhibit ID: <input type="number" name="exhibit-id-find-article" min="1000" max="9999" required>
            <input type="submit" value="Find Articles" name="submit-find-article-on-display"></p>
        </form>

        <form method="GET" id="count-article-on-display-request" action="archivist_display_article.php">
            <input type="hidden" id="count-article-on-display-request" name="count-article-on-display-request">
            <input type="submit" value="Count Articles Per Exhibit" name="submit-count-article-on-display"></p>
            <br/>
        </form>

        <h3>Add Article to Exhibit</h3>
        <form method="POST" id="add-article-to-exhibit-request" action="archivist_display_article.php">
            <input type="hidden" id="add-article-to-exhibit-request" name="add-article-to-exhibit-request">
            exhibit ID: <input type="number" name="exhibit-id-display-article" min="1000" max="9999" required>
            <br/><br/>
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
            <input type="submit" value="Add Article" name="submit-add-article-to-exhibit"></p>
        </form>

        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
            if (array_key_exists('submit-search-exhibit', $request_method)) {
                    handleSearchExhibitsRequest();
                } else if (array_key_exists('submit-find-article-on-display', $request_method)) {
                    handleFindArticlesOnDisplayRequest();
                } else if (array_key_exists('submit-count-article-on-display', $request_method)) {
                    handleCountArticlesOnDisplayRequest();
                } else if  (array_key_exists('submit-add-article-to-exhibit', $request_method)) {
                    handleAddArticleToExhibitRequest();
                }
                disconnectFromDB(); 
            }
        }

        function handleSearchExhibitsRequest() {
            global $db_conn;

            $search_term = $_GET['search-term'];

            $result = executePlainSQL(
                "SELECT exhibit_id, exhibit_name, start_date, end_date
                FROM exhibit
                WHERE UPPER(exhibit_name) LIKE '%' || UPPER('" . $search_term . "') || '%'");

            echo '<br/><br/>';
            echo '<p>The following exhibits match ' . $search_term . ':</p>';
            printResults($result);
        }

        function handleFindArticlesOnDisplayRequest() {
            global $db_conn;

            $exhibit_id = $_GET['exhibit-id-find-article'];

            $result = executePlainSQL(
                "SELECT 
                    e.exhibit_id, e.exhibit_name, e.start_date, e.end_date,
                    a.article_id, a.article_name, a.storage_location
                FROM exhibit e, displays d, article a
                WHERE
                    e.exhibit_id = " . $exhibit_id . " AND
                    e.exhibit_id = d.exhibit_id AND
                    d.article_id = a.article_id");

            echo '<br/><br/>';
            echo '<p>The following articles are currently on display:</p>';
            printResults($result);
        }

        function handleCountArticlesOnDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT e.exhibit_id, COUNT(*)
                FROM exhibit e, displays d, article a
                WHERE e.exhibit_id = d.exhibit_id AND d.article_id = a.article_id
                GROUP BY e.exhibit_id");

            echo '<br/><br/>';
            echo '<p>The number of articles currently on display in each exhibit is:</p>';
            printResults($result);
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
                
                printArticleOnDisplay($article_id, $exhibit_id, '<p>The following article has been put on display:</p>');
            } else {
                echo '<br/><br/>';
                echo '<p>Article ' . $article_id . ' is already on display in exhibit ' . $exhibit_id . '.</p>';
            }

            oci_commit($db_conn);            
        }

        function printArticleOnDisplay($article_id, $exhibit_id, $output_string) {
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

            echo '<br/><br/>';
            echo $output_string;
            printResults($result);
        }

        // process database requests
        if (isset($_POST['submit-add-article-to-exhibit'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['submit-search-exhibit']) || isset($_GET['submit-find-article-on-display']) || isset($_GET['submit-count-article-on-display'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>
