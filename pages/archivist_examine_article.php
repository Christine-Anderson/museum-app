<!--
    Archivist view examine article page
    Archivist can update, insert, and delete article examination records
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivist examine article</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'archivist_sidebar.php'
    ?>

    <div class="content">
        <h2>Examine Article</h2>

        <h3>Update Article Examination Records</h3>
        <form method="GET" id="examine-article-update-request" action="archivist_examine_article.php">
			<select name="article-update-option" id="article-type-update">  
				<option value="article-condition-update">Article Condition</option>
				<option value="artwork-update">Artwork</option>
				<option value="text-update">Text</option>
                <option value="photo-update">Photo</option>
				<option value="artifact-update">Artifact</option>
				<option value="natural-specimen-update">Natural Specimen</option>
			</select>
            <input type="submit" value="Select" name="submit-examine-article-update"></p>
            <br/>
        </form>

        <h3>Delete Article Examination Records</h3>
        <form method="GET" id="examine-article-delete-request">
            <input type="hidden" id="examine-article-delete-request" name="examine-article-delete-request">
            article ID: <input type="number" name="article-id" min="10000" max="99999" required>
            <br/><br/>
			<select name="article-delete-option" id="article-type-delete">
				<option value="artwork-delete">Artwork</option>
				<option value="text-delete">Text</option>
                <option value="photo-delete">Photo</option>
				<option value="artifact-delete">Artifact</option>
				<option value="natural-specimen-delete">Natural Specimen</option>
            </select>
            <input type="submit" value="Select" name="submit-examine-article-delete"></p>
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
                } else if (array_key_exists('submit-text-update', $request_method)) {
                    handleUpdateTextRequest();
                } else if (array_key_exists('submit-new-text', $request_method)) {
                    handleNewTextRequest();
                } else if (array_key_exists('submit-photo-update', $request_method)) {
                    handleUpdatePhotoRequest();
                } else if (array_key_exists('submit-new-photo', $request_method)) {
                    handleNewPhotoRequest();
                } else if (array_key_exists('submit-artifact-update', $request_method)) {
                    handleUpdateArtifactRequest();
                } else if (array_key_exists('submit-new-artifact', $request_method)) {
                    handleNewArtifactRequest();
                } else if (array_key_exists('submit-natural-specimen-update', $request_method)) {
                    handleUpdateNaturalSpecimenRequest();
                } else if (array_key_exists('submit-new-natural-specimen', $request_method)) {
                    handleNewNaturalSpecimenRequest();
                } else if (array_key_exists('submit-examine-article-delete', $request_method)) {
                    handleDeleteRequest("artwork");
                } else if (array_key_exists('confirm-delete-request', $request_method)) {
                    confirmDeleteRequest();
                } else if (array_key_exists('reject-delete-request', $request_method)) {
                    rejectDeleteRequest();
                }
                disconnectFromDB(); 
            }
        }

        function handleFormSubmissionRequest() {
            $article_update = $_GET['article-update-option'];

            switch ($article_update) {
                case 'article-condition-update':
                    renderArticleConditionForm();
                    break;
                case 'artwork-update': 
                    renderArtworkForm();
                    break;
                case 'text-update':
                    renderTextForm();
                    break;
                case 'photo-update':
                    renderPhotoForm();
                    break;
                case 'artifact-update':
                    renderArtifactForm();
                    break;
                case 'natural-specimen-update':
                    renderNaturalSpecimenForm();
                    break;
                default:
                    echo '<br/><br/>';
                    echo "<p>Please select what you would like to update.</p>";
            }
        }

        function handleUpdateArticleConditionRequest() {
            global $db_conn;

            $article_id = $_POST['article-id'];
            $article_condition = $_POST['article-condition-option'];

            switch ($article_condition) {
                case 'excellent':
                    $article_condition = "Excellent";
                    break;
                case 'fair':
                    $article_condition = "Fair";
                    break;
                case 'poor':
                    $article_condition = "Poor";
                    break;
                default:
                    $article_condition = "Good";
            }

            executePlainSQL(
                "UPDATE article 
                SET article_condition = '" . $article_condition . "' 
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            printGivenArticle($article_id, "article");
        }

        function handleUpdateArtworkRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];
            $artist = $_POST['artist'];
            $year_made = $_POST['year-made'];
            $medium = $_POST['medium'];

            executePlainSQL(
                "UPDATE artwork
                SET artist = '" . $artist . "',
                    year_made = " . $year_made . ",
                    medium = '" . $medium . "'
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record updated successfully.</p>';
            printGivenArticle($article_id, "artwork");
        }

        function handleNewArtworkRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];

            $tuple = array (
                ":article_id" => $article_id,
                ":artist" => $_POST['artist'],
                ":year_made" => $_POST['year-made'],
                ":medium" => $_POST['medium']
            );

            $all_tuples = array (
                $tuple
            );

            executeBoundSQL(
                "INSERT INTO artwork
                VALUES (:article_id, :artist, :year_made, :medium)", $all_tuples);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record added successfully.</p>';
            printGivenArticle($article_id, "artwork");
        }

        function handleUpdateTextRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];
            $author = $_POST['author'];
            $year_published = $_POST['year-published'];

            executePlainSQL(
                "UPDATE text
                SET author = '" . $author . "',
                    year_published = " . $year_published . ",
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record updated successfully.</p>';
            printGivenArticle($article_id, "text");
        }

        function handleNewTextRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];

            $tuple = array (
                ":article_id" => $article_id,
                ":author" => $_POST['author'],
                ":year_published" => $_POST['year-published']
            );

            $all_tuples = array (
                $tuple
            );

            executeBoundSQL(
                "INSERT INTO text
                VALUES (:article_id, :author, :year_published)", $all_tuples);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record added successfully.</p>';
            printGivenArticle($article_id, "text");
        }

        function handleUpdatePhotoRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];
            $year_taken = $_POST['year-taken'];
            $location_taken = $_POST['location-taken'];

            executePlainSQL(
                "UPDATE photo
                SET year_taken = '" . $year_taken . "',
                    location_taken = " . $location_taken . ",
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record updated successfully.</p>';
            printGivenArticle($article_id, "photo");
        }

        function handleNewPhotoRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];

            $tuple = array (
                ":article_id" => $article_id,
                ":year_taken" => $_POST['year-taken'],
                ":location_taken" => $_POST['location-taken']
            );

            $all_tuples = array (
                $tuple
            );

            executeBoundSQL(
                "INSERT INTO photo
                VALUES (:article_id, :year_taken, :location_taken)", $all_tuples);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record added successfully.</p>';
            printGivenArticle($article_id, "photo");
        }

        function handleUpdateArtifactRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];
            $estimated_year = $_POST['estimated-year'];
            $region_of_origin = $_POST['region-of-origin'];
            $material = $_POST['material'];

            executePlainSQL(
                "UPDATE artifact
                SET estimated_year = '" . $estimated_year . "',
                    region_origin = " . $region_of_origin . ",
                    material = '" . $material . "'
                WHERE article_id = " . $article_id);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record updated successfully.</p>';
            printGivenArticle($article_id, "artifact");
        }

        function handleNewArtifactRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];

            $tuple = array (
                ":article_id" => $article_id,
                ":estimated_year" => $_POST['estimated-year'],
                ":region_of_origin" => $_POST['region-of-origin'],
                ":material" => $_POST['material']
            );

            $all_tuples = array (
                $tuple
            );

            executeBoundSQL(
                "INSERT INTO artifact
                VALUES (:article_id, :estimated_year, :region_of_origin, :material)", $all_tuples);

            oci_commit($db_conn);

            echo '<br/><br/>';
            echo '<p>Record added successfully.</p>';
            printGivenArticle($article_id, "artifact");
        }

        function handleUpdateNaturalSpecimenRequest() {
            global $db_conn; 

            $article_id = $_POST['article-id'];
            $species_name = $_POST['species-name'];
            $native_to = $_POST['native-to'];
            $time_period = $_POST['time-period'];

            // update or insert into species
            executePlainSQL(
                "MERGE INTO species target
                USING (SELECT '" . $species_name . "' AS soi FROM dual) source
                ON (target.species_name = source.soi)
                WHEN MATCHED THEN
                    UPDATE SET target.native_to = '" . $native_to . "'
                    WHERE target.species_name = source.soi
                WHEN NOT MATCHED THEN
                    INSERT (species_name, native_to)
                    VALUES (source.soi, '" . $native_to . "')");

            // update or insert into natural specimen
            executePlainSQL(
                "MERGE INTO naturalspecimen target
                USING (SELECT '" . $article_id . "' AS aoi FROM dual) source
                ON (target.article_id = source.aoi)
                WHEN MATCHED THEN
                    UPDATE SET 
                        species_name = '" . $species_name . "',
                        time_period = '" . $time_period . "'
                    WHERE target.article_id = source.aoi
                WHEN NOT MATCHED THEN
                    INSERT (article_id, species_name, time_period)
                    VALUES (source.aoi, '" . $species_name . "', '" . $time_period . "')");

            oci_commit($db_conn);


            echo '<br/><br/>';
            echo '<p>Record updated successfully.</p>';
            printGivenArticle($article_id, "naturalspecimenspecies");
        }

        function handleDeleteRequest($table) {
            global $db_conn; 

            $article_id = $_GET['article-id'];

            echo '<br/><br/> <p>Are you sure you want to delete the following record?</p>';
            printGivenArticle($article_id, "artwork");

            $delete_stmt =
                "DELETE 
                FROM " . $table . "
                WHERE article_id = " . $article_id;

            confirmDeleteForm($delete_stmt);
        }

        function confirmDeleteRequest() {
            global $db_conn; 

            $delete_stmt = $_POST['delete-stmt'];
            executePlainSQL($delete_stmt);

            oci_commit($db_conn);
            echo '<br/><br/> <p>Record deleted successfully.</p>';
        }

        function rejectDeleteRequest() {
            global $db_conn;
            echo '<br/><br/> <p>Delete cancelled.</p>';
        }

        function renderArticleConditionForm() {
            echo '<br/><br/>';
            echo '<p>Please enter the article condition:</p>';
            echo '<form method="POST" id="archivist-update-article-condition">';
            echo '<input type="hidden" id="update-article-condition-request" name="update-article-condition-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br/><br/>';
            echo '<select name="article-condition-option" id="article-condition">';
            echo '<option value="excellent">Excellent</option>';
            echo '<option value="good">Good</option>';
            echo '<option value="fair">Fair</option>';
            echo '<option value="poor">Poor</option>';
            echo '</select>';
            echo '&nbsp;&nbsp;';
            echo '<input type="submit" value="Update" name="submit-article-update-condition"></p>';
            echo '</form>';
        }

        function renderArtworkForm() {
            echo '<br/><br/>';
            echo '<p>Please enter the following information for the artwork:</p>';
            echo '<form method="POST" id="archivist-update-artwork">';
            echo '<input type="hidden" id="update-artwork-request" name="update-artwork-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br/><br/>';
            echo 'Artist: <input type="text" name="artist" > <br/><br/>';
            echo 'Year Made: <input type="number" name="year-made" min="1" max="9999"> <br/><br/>';
            echo 'Medium: <input type="text" name="medium"> <br/><br/>';
            echo '<input type="submit" value="Update Record" name="submit-artwork-update">';
            echo '</form>';
        }

        function renderTextForm() {
            echo '<br/><br/>';
            echo '<p>Please enter the following information for the text:</p>';
            echo '<form method="POST" id="archivist-update-text">';
            echo '<input type="hidden" id="update-text-request" name="update-text-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br/><br/>';
            echo 'Author: <input type="text" name="author" > <br/><br/>';
            echo 'Year Published: <input type="number" name="year-published" min="1" max="9999"> <br/><br/>';
            echo '<input type="submit" value="Update Record" name="submit-text-update">';
            echo '</form>';
        }

        function renderPhotoForm() {
            echo '<br/><br/>';
            echo '<p>Please enter the following information for the photo:</p>';
            echo '<form method="POST" id="archivist-update-photo">';
            echo '<input type="hidden" id="update-photo-request" name="update-photo-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br/><br/>';
            echo 'Year Taken: <input type="number" name="year-taken" min="1" max="9999"> <br/><br/>';
            echo 'Location Taken: <input type="text" name="location-taken" > <br/><br/>';
            echo '<input type="submit" value="Update Record" name="submit-photo-update">';
            echo '</form>';
        }

        function renderArtifactForm() {
            echo '<br/><br/>';
            echo '<p>Please enter the following information for the artifact:</p>';
            echo '<form method="POST" id="archivist-update-artifact">';
            echo '<input type="hidden" id="update-artifact-request" name="update-artifact-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br/><br/>';
            echo 'Estimated Year: <input type="text" name="estimated-year" > <br/><br/>';
            echo 'Region Of Origin: <input type="text" name="region-of-origin"> <br/><br/>';
            echo 'Material: <input type="text" name="material"> <br/><br/>';
            echo '<input type="submit" value="Update Record" name="submit-artifact-update">';
            echo '</form>';
        }

        function renderNaturalSpecimenForm() {
            echo '<br/><br/>';
            echo '<p>Please enter the following information for the natural specimen:</p>';
            echo '<form method="POST" id="archivist-update-natural-specimen">';
            echo '<input type="hidden" id="update-natural-specimen-request" name="update-natural-specimen-request">';
            echo 'article ID: <input type="number" name="article-id" min="10000" max="99999" required>';
            echo '<br/><br/>';
            echo 'Species Name: <input type="text" name="species-name"> <br/><br/>';
            echo 'Native To: <input type="text" name="native-to"> <br/><br/>';
            echo 'Time Period: <input type="text" name="time-period"> <br/><br/>';
            echo '<input type="submit" value="Update Record" name="submit-natural-specimen-update">';
            echo '</form>';
        }

        function confirmDeleteForm($delete_stmt) {
            echo '<br/><br/>';
            echo '<form method="POST" id="archivist-update-artwork">';
            echo '<input type="hidden" name="delete-stmt" value="' . $delete_stmt . '">';
            echo '<input type="submit" value="Yes" name="confirm-delete-request">';
            echo '&nbsp;&nbsp;';
            echo '<input type="submit" value="No" name="reject-delete-request"></p>';
            echo '</form>';
        }    

        function printGivenArticle($article_id, $table_name) {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT *
                FROM " . $table_name . "
                WHERE article_id = " . $article_id);

            printResults($result);
        }

        // process render form requests
        if(isset($_GET['submit-examine-article-update'])) {
            if (array_key_exists('submit-examine-article-update', $_GET)) {
                handleFormSubmissionRequest();
            }
        }

        // process database requests
        if (isset($_POST['submit-article-update-condition']) || isset($_POST['submit-artwork-update']) || isset($_POST['submit-text-update'])
            || isset($_POST['submit-photo-update']) || isset($_POST['submit-artifact-update']) || isset($_POST['submit-natural-specimen-update'])
            || isset($_POST['confirm-delete-request']) || isset($_POST['reject-delete-request'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['submit-examine-article-delete'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>
