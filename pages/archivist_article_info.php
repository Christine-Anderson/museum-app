<!--
    Archivist view article information page
    Archivist can view location and storage condition of a given article
    TODO add more functionality
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
            <br /><br />

            <label for="find">Find:</label>
				<select name="find-option" id="find">
					<option value="location">Current Location</option>
					<option value="storage-condition">Storage Conditions</option>
					<option value="article-examination-detail">Article Examination Details</option>
				</select>
			<br></br>

            <input type="submit" value="Search" name="search-article-info"></p>
        </form>

        <p>TODO: sort articles by article-examination-detail</p>

        <!-- <form method="POST" action="owner_profile.php">
            <input type="hidden" id="update-profile-request" name="update-profile-request">
            owner ID: <input type="number" name="article-id" min="1000" max="9999" required>
            <br /><br />
            Name: <input type="text" name="name"> <br /><br />
            <input type="submit" value="Update" name="update-profile-submit"></p>
        </form> -->
            
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

        // function handleUpdateProfileRequest() {
        //     global $db_conn;

        //     $article_id = $_POST['article-id'];
        //     $name = $_POST['name'];

        //     executePlainSQL(
        //         "UPDATE owner 
        //         SET name = '" . $name . "' 
        //         WHERE owner_id = " . $article_id);

        //     oci_commit($db_conn);

        //     viewOwnerProfile($article_id);
        // }

        function handleArticleInfoRequest() {
            global $db_conn;
            $article_id = $_GET['article-id'];
            $dropdown_value = $_GET['find-option'];

            if ($dropdown_value == 'article-examination-detail') {
                getArticleLocation($article_id);
            } else if ($dropdown_value == 'storage-condition') {
                getArticleStorageConditions($article_id);
            } else { // $dropdown_value == 'article-examination-detail'
                getArticleDetails($article_id);
            }
        }

        function getArticleLocation($article_id) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT article_id, name, storage_location
                FROM article
                WHERE article_id = " . $article_id . "");

            printResults($result);
        }

        function getArticleStorageConditions($article_id) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT article_id, name, uv_protection, temp_control, humidity_control
                FROM article
                WHERE article_id = " . $article_id . "");

            printResults($result);
        }

        function getArticleDetails($article_id) {
            global $db_conn;

            $result = executePlainSQL( //fix me come back to this one
                "SELECT *
                FROM article");
                // FROM article a, artwork art, text t, photo p, artifact old, naturalspecimen ns, species s
                // WHERE   a.article_id = " . $article_id . ") AND
                //         a.article_id = art.article_id OR
                //         a.article_id = t.article_id OR
                //         a.article_id = p.article_id OR
                //         a.article_id = old.article_id OR
                //         (a.article_id = ns.article_id AND
                //         ns.species_name = s.species_name)");

            printResults($result);
        }

        if (isset($_POST['update-profile-request'])) {
            handleRequest($_POST);
        } else if (isset($_GET['article-info-request'])) {
            handleRequest($_GET);
        }
        ?>  
    </div>
</body>
</html>
