<!--
    Curator Collection Management page
    TODO
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Management</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'curator_sidebar.php'
    ?>

<div class="content">
        <h2>Collection Management</h2>

        <h3>Add Article to Collection</h3>
        <form method="POST" id="add-article-to-collection-request" action="curator_collection_management.php">
            <input type="hidden" id="add-article-to-collection-request" name="add-article-to-collection-request">
            collection ID: <input type="number" name="collection-id-add" min="1" max="100" required>
            <br/><br/>
            article ID: <input type="number" name="article-id-add" min="10000" max="99999" required>
            <br/><br/>
            <input type="submit" value="Add Article" name="submit-add-article-to-collection"></p>
            <br/>
        </form>

        <h3>Remove Article from Collection</h3>
        <form method="GET" id="remove-article-from-collection-request" action="curator_collection_management.php">
            <input type="hidden" id="remove-article-from-collection-request" name="remove-article-from-collection-request">
            collection ID: <input type="number" name="collection-id-remove" min="1" max="100" required>
            <br/><br/>
            article ID: <input type="number" name="article-id-remove" min="10000" max="99999" required>
            <br/><br/>
            <input type="submit" value="Remove Article" name="submit-remove-article-from-collection"></p>
            </br>
        </form>

        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-add-article-to-collection', $request_method)) {
                    handleAddArticleCollectionRequest();
                } else if (array_key_exists('submit-remove-article-from-collection', $request_method)) {
                    handleRemoveArticleFromCollectionRequest();
                } else if (array_key_exists('confirm-delete-request', $request_method)) {
                    confirmDeleteRequest();
                } else if (array_key_exists('reject-delete-request', $request_method)) {
                    rejectDeleteRequest();
                }
                disconnectFromDB(); 
            }
        }

        function handleAddArticleCollectionRequest() {
            global $db_conn; 

            $collection_id = $_POST['collection-id-add'];
            $article_id = $_POST['article-id-add'];

            // if article does not already exist in collection, insert it
            $insert_result = executePlainSQL(
                "MERGE INTO contains target
                USING (SELECT ". $collection_id . " AS coi, " . $article_id . " AS aoi FROM dual) source
                ON (target.collection_id = source.coi AND target.article_id = source.aoi)
                WHEN NOT MATCHED THEN
                    INSERT (article_id, collection_id)
                    VALUES (source.aoi, source.coi)");

            $rows = oci_num_rows($insert_result);

            if ($rows > 0) {
                echo '<p>The following article has been added to the collection:</p>';
                printArticleInCollection($collection_id, $article_id);
            } else {
                echo '<p>Article ' . $article_id . ' is already in collection ' . $collection_id . '.</p>';
            }

            oci_commit($db_conn);            
        }

        function handleRemoveArticleFromCollectionRequest() {
            global $db_conn;

            $collection_id = $_GET['collection-id-remove'];
            $article_id = $_GET['article-id-remove'];

            echo '<p>Are you sure you want to remove the following article from the collection?</p>';
            printArticleInCollection($collection_id, $article_id);

            $delete_stmt =
                "DELETE 
                FROM contains
                WHERE
                    article_id = " . $article_id . " AND 
                    collection_id = " . $collection_id;

            confirmDeleteForm($delete_stmt);
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

        function confirmDeleteForm($delete_stmt) {
            echo '<br/><br/>';
            echo '<form method="POST" id="remove-article-request">';
            echo '<input type="hidden" name="delete-stmt" value="' . $delete_stmt . '">';
            echo '<input type="submit" value="Yes" name="confirm-delete-request">';
            echo '&nbsp;&nbsp;';
            echo '<input type="submit" value="No" name="reject-delete-request"></p>';
            echo '</form>';
        } 

        // process database requests
        if (isset($_POST['submit-add-article-to-collection']) || isset($_POST['confirm-delete-request']) || isset($_POST['reject-delete-request'])) {
            handleDatabaseRequest($_POST);
        } else if (isset($_GET['submit-remove-article-from-collection'])) {
            handleDatabaseRequest($_GET);
        }
        ?>
    </div>
</body>
</html>