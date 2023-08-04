<!--
    Title
    Description TODO
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivist view main page</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'archivist_sidebar.php'
    ?>

    <div class="content">
        <h2>Update Article Examination Records</h2>

        <form method="GET" id="examine-article-update-request">
				<select name="article-update-option" id="article-type">
					<option value="article-condition">Article Condition</option>
					<option value="artwork">Artwork</option>
					<option value="text">Text</option>
                    <option value="photo">Photo</option>
					<option value="artifact">Artifact</option>
					<option value="natural-specimen">Natural Specimen</option>
				</select>

            <input type="submit" value="Select" name="submit-examine-article-update"></p>
        </form>
    
        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-article-update-condition', $request_method)) {
                    handleUpdateArticleConditionRequest();
                } else if (array_key_exists('submit-artwork-update', $request_method)) {
                    handleUpdateArtworkRequest();
                } else if (array_key_exists('submit-new-artwork', $request_method)) {
                    handleNewArtworkRequest();
                }
                disconnectFromDB(); 
            }
        }

        function handleFormSubmissionRequest() {
            $article_update = $_GET['article-update-option'];

            switch ($article_update) {
                case 'article-condition':
                    renderArticleConditionForm();
                    break;
                case 'artwork': 
                    renderArtworkForm();
                    break;
                case 'text':
                    renderTextForm();
                    break;
                case 'photo':
                    renderPhotoForm();
                    break;
                case 'artifact':
                    renderArtifactForm();
                    break;
                case 'natural-specimen':
                    renderNaturalSpecimenForm();
                    break;
                default:
                    echo "Please select what you would like to update.";
            }
        }

        function renderArticleConditionForm() {
            echo '<form method="POST" id="archivist-update-article-condition">';
            echo '<input type="hidden" id="update-article-condition-request" name="update-article-condition-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br /><br />';
            echo '<select name="article-condition-option" id="article-condition">';
            echo '<option value="excellent">Excellent</option>';
            echo '<option value="good">Good</option>';
            echo '<option value="fair">Fair</option>';
            echo '<option value="poor">Poor</option>';
            echo '</select>';
            echo '<input type="submit" value="Update" name="submit-article-update-condition"></p>';
            echo '</form>';
        }

        function renderArtworkForm() {
            echo '<form method="POST" id="archivist-update-artwork">';
            echo '<input type="hidden" id="update-artwork-request" name="update-artwork-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br /><br />';
            echo 'Artist: <input type="text" name="artist" > <br /><br />';
            echo 'Year Made: <input type="number" name="year-made" min="1" max="9999"> <br /><br />';
            echo 'Medium: <input type="text" name="medium"> <br /><br />';
            echo '<input type="submit" value="Update Record" name="submit-artwork-update">';
            echo '<input type="submit" value="New Record" name="submit-new-artwork"></p>';
            echo '</form>';
        }

        function handleUpdateArticleConditionRequest() {
            global $db_conn;

            $article_id = $_POST['article-id'];
            $condition = $_POST['article-condition-option'];

            switch ($condition) {
                case 'excellent':
                    $condition = "Excellent";
                    break;
                case 'fair':
                    $condition = "Fair";
                    break;
                case 'poor':
                    $condition = "Poor";
                    break;
                default:
                    $condition = "Good";
            }

            executePlainSQL(
                "UPDATE article 
                SET condition = '" . $condition . "' 
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            viewChange($article_id, "article");
        }

        function handleUpdateArtworkRequest() { //TODO and test debug all artwork stuff
            global $db_conn; 

            $tuple = array (
                ":article_id" => $_POST['article-id'],
                ":artist" => $_POST['artist'],
                ":year_made" => $_POST['year-made'],
                ":medium" => $_POST['medium']
            );

            $all_tuples = array (
                $tuple
            );

            executeBoundSQL("INSERT INTO artwork
                            VALUES (:article_id, :artist, :year_made, :medium)", $all_tuples);

            oci_commit($db_conn);

            viewChange($article_id, "artwork");
        }

        function handleNewArtworkRequest() {
            global $db_conn; 

            $tuple = array (
                ":article_id" => $_POST['article-id'],
                ":artist" => $_POST['artist'],
                ":year_made" => $_POST['year-made'],
                ":medium" => $_POST['medium']
            );

            $all_tuples = array (
                $tuple
            );

            executeBoundSQL("INSERT INTO artwork
                            VALUES (:article_id, :artist, :year_made, :medium)", $all_tuples);

            oci_commit($db_conn);

            viewChange($article_id, "artwork");
        }

        function viewChange($article_id, $table) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT *
                FROM " . $table . "
                WHERE article_id = " . $article_id . "");

            printResults($result);
        }

        // process render form requests
        if(isset($_GET['submit-examine-article-update'])) {
            if (array_key_exists('submit-examine-article-update', $_GET)) {
                handleFormSubmissionRequest();
            }
        }

        // process database requests
        if (isset($_POST['submit-article-update-condition']) || isset($_POST['submit-artwork-update']) || isset($_POST['submit-new-artwork'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['to-do'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>
