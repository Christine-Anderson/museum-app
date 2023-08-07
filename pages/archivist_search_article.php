<!--
    Archivist search article page
    Archivist can search location, storage condition, or examination details of a given article
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivist search articles</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'archivist_sidebar.php'
    ?>

    <div class="content">
        <h2>Search Articles</h2>

        <h3>Search by Name</h3>
        <form method="GET" id="article-search-by-name-request" action="archivist_search_article.php">
            <input type="hidden" id="article-search-by-name-request" name="article-search-by-name-request">
            <input type="text" name="search-term">
            <input type="submit" value="Search Articles" name="submit-article-search-by-name"></p>
            <br/>
        </form>

        <h3>Search by ID</h3>
        <form method="GET" id="article-search-by-id-request" action="archivist_search_article.php">
            <input type="hidden" id="article-search-by-id-request" name="article-search-by-id-request">
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
            <label for="find">Find:</label>
				<select name="find-option" id="find">
					<option value="article-location">Current Location</option>
					<option value="storage-condition">Storage Conditions</option>
					<option value="article-examination-detail">Article Examination Details</option>
				</select>
			<br/><br/>
            <input type="submit" value="Search" name="submit-article-search-by-id"></p>
        </form>
            
        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-article-search-by-id', $request_method)) {
                    handleSearchByIDRequest();
                } else if (array_key_exists('submit-article-search-by-name', $request_method)) {
                    handleSearchByNameRequest();
                }
                disconnectFromDB();
            }
        }

        function handleSearchByNameRequest() {
            global $db_conn;

            $search_term = $_GET['search-term'];

            $result = executePlainSQL(
                "SELECT article_id, article_name
                FROM article
                WHERE UPPER(article_name) LIKE '%' || UPPER('" . $search_term . "') || '%'");

            echo '<br/><br/>';
            echo '<p>The following articles match ' . $search_term . ':</p>';
            printResults($result);
        }

        function handleSearchByIDRequest() {
            global $db_conn;
            $article_id = $_GET['article-id'];
            $dropdown_value = $_GET['find-option'];

            if ($dropdown_value == 'article-location') {
                getArticleLocation($article_id);
            } else if ($dropdown_value == 'storage-condition') {
                getArticleStorageConditions($article_id);
            } else if ($dropdown_value == 'article-examination-detail') { 
                getArticleDetails($article_id);
            } else {
                echo "Please select a search option.";
            }
        }

        function getArticleLocation($article_id) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT article_id, article_name, storage_location
                FROM article
                WHERE article_id = " . $article_id . "");

            echo '<br/><br/>';
            printResults($result);
        }

        function getArticleStorageConditions($article_id) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT article_id, article_name, uv_protection, temp_control, humidity_control
                FROM article
                WHERE article_id = " . $article_id);

            echo '<br/><br/>';
            printResults($result);
        }

        function getArticleDetails($article_id) {
            global $db_conn;

            $artwork_result = executePlainSQL(
                "SELECT *
                FROM articleartwork
                WHERE article_id = " . $article_id);

            $text_result = executePlainSQL(
                "SELECT *
                FROM articletext
                WHERE article_id = " . $article_id);

            $photo_result = executePlainSQL(
                "SELECT *
                FROM articlephoto
                WHERE article_id = " . $article_id);
            
            $artifact_result = executePlainSQL(
                "SELECT *
                FROM articleartifact
                WHERE article_id = " . $article_id);
            
            $naturalspecimen_result = executePlainSQL(
                "SELECT *
                FROM articlenaturalspecimen
                WHERE article_id = " . $article_id);

            printArticleDetails($artwork_result, $text_result, $photo_result, $artifact_result, $naturalspecimen_result);
        }

        function printArticleDetails($artwork_result, $text_result, $photo_result, $artifact_result, $naturalspecimen_result){
            oci_fetch_all($artwork_result, $artwork_rows, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_fetch_all($text_result, $text_rows, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_fetch_all($photo_result, $photo_rows, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_fetch_all($artifact_result, $artifact_rows, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_fetch_all($naturalspecimen_result, $naturalspecimen_rows, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            
            if (!$artwork_rows && !$text_rows && !$photo_rows && !$artifact_rows && !$naturalspecimen_rows) {
                echo '<br/><br/>';
                echo "No results found";
            } else {
                if ($artwork_result) {
                    echo '<br/><br/>';
                    autogenerateTable($artwork_rows);
                }

                if ($text_result) {
                    echo '<br/><br/>';
                    autogenerateTable($text_rows);
                }

                if ($photo_result) {
                    echo '<br/><br/>';
                    autogenerateTable($photo_rows);
                }

                if ($artifact_result) {
                    echo '<br/><br/>';
                    autogenerateTable($artifact_rows);
                }

                if ($naturalspecimen_result) {
                    echo '<br/><br/>';
                    autogenerateTable($naturalspecimen_rows);
                }

            }
        }

        if (isset($_GET['article-search-by-id-request']) || isset($_GET['article-search-by-name-request'])) {
            handleRequest($_GET);
        }
        ?>  
    </div>
</body>
</html>
