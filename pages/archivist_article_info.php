<!--
    Archivist view article information page
    Archivist can view location, storage condition, or examination details of a given article
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivist view article information page</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'archivist_sidebar.php'
    ?>

    <div class="content">
        <h2>Search Article Information</h2>

        <form method="GET" action="archivist_article_info.php">
            <input type="hidden" id="article-info-request" name="article-info-request">
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
            <label for="find">Find:</label>
				<select name="find-option" id="find">
					<option value="article-location">Current Location</option>
					<option value="storage-condition">Storage Conditions</option>
					<option value="article-examination-detail">Article Examination Details</option>
				</select>
			<br/><br/>
            <input type="submit" value="Search" name="search-article-info"></p>
        </form>
            
        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('search-article-info', $request_method)) {
                    handleArticleInfoRequest();
                }
                disconnectFromDB();
            }
        }

        function handleArticleInfoRequest() {
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

            printResults($result);
        }

        function getArticleStorageConditions($article_id) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT article_id, article_name, uv_protection, temp_control, humidity_control
                FROM article
                WHERE article_id = " . $article_id);

            printResults($result);
        }

        function getArticleDetails($article_id) {
            global $db_conn;

            $view_names = ["articleofinterest", "naturalspecimencomplete"];

            // TODO refactor into different functions
            executePlainSQL("DROP VIEW " . $view_names[0]);

            executePlainSQL(
                "CREATE VIEW " . $view_names[0] . " AS
                SELECT article_id, article_name, article_condition
                FROM article
                WHERE article_id = " . $article_id);

            // if (!hasView($view_name)) { // TODO debug checking if a view exists
            executePlainSQL(
                "CREATE VIEW " . $view_names[1] . " AS
                SELECT ns.*, s.native_to
                FROM naturalspecimen ns, species s
                WHERE ns.species_name = s.species_name");
            // }
            
            $result = executePlainSQL(
                "SELECT
                    aoi.*,
                    art.artist, art.year_made, art.medium,
                    t.author, t.year_published,
                    p.year_taken, p.location_taken,
                    a.estimated_year, a.region_of_origin, a.material,
                    nsc.species_name, nsc.native_to, nsc.time_period
                FROM " . $view_names[0] . " aoi
                LEFT JOIN artwork art ON aoi.article_id = art.article_id 
                LEFT JOIN text t ON aoi.article_id = t.article_id
                LEFT JOIN photo p ON aoi.article_id = p.article_id
                LEFT JOIN artifact a ON aoi.article_id = a.article_id
                LEFT JOIN " . $view_names[1] . " nsc ON aoi.article_id = nsc.article_id
                WHERE aoi.article_id = " . $article_id);

            printResults($result); // TODO fix table auto print
        }

        

        if (isset($_GET['article-info-request'])) {
            handleRequest($_GET);
        }
        ?>  
    </div>
</body>
</html>
