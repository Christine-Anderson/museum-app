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
                }
                disconnectFromDB();
            }
        }

        handleFormSubmissionRequest() {
            $article_update = $_GET['article-update-option'];

            switch ($article_update) {
                case 'article-condition':
                    renderArticleConditionForm();
                    break;
                case 'artwork': //TODO everything from here down
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

            viewArticleCondition($article_id);
        }

        function viewArticleCondition($article_id) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT article_id, name, condition
                FROM article
                WHERE article_id = " . $article_id . "");

            printResults($result);
        }

        // process render form requests
        if(isset($_GET['submit-examine-article-update'])) {
            handleFormSubmissionRequest()
        }

        // process database requests
        if (isset($_POST['submit-article-update-condition'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['to-do'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>
