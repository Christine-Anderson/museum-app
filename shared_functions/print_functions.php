<!--
    Print functions for outputting database search, update, and delete results to html table
-->

<?php

function printArticleDetails($article_id, $artwork_result, $text_result, $photo_result, $artifact_result, $naturalspecimen_result){
    global $db_conn;

    oci_fetch_all($artwork_result, $artwork_rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    oci_fetch_all($text_result, $text_rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    oci_fetch_all($photo_result, $photo_rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    oci_fetch_all($artifact_result, $artifact_rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    oci_fetch_all($naturalspecimen_result, $naturalspecimen_rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    
    echo '<p>The following examination records exist for article ID ' . $article_id . ':</p>';

    if (!$artwork_rows && !$text_rows && !$photo_rows && !$artifact_rows && !$naturalspecimen_rows) {
        echo "No results found";
    } else {
        if ($artwork_rows) {
            autogenerateTable($artwork_rows);
        }

        if ($text_rows) {
            autogenerateTable($text_rows);
        }

        if ($photo_rows) {
            autogenerateTable($photo_rows);
        }

        if ($artifact_rows) {
            autogenerateTable($artifact_rows);
        }

        if ($naturalspecimen_rows) {
            autogenerateTable($naturalspecimen_rows);
        }
    }
}

function printGivenArticle($article_id, $table_name) {
    global $db_conn;

    $result = executePlainSQL(
        "SELECT *
        FROM " . $table_name . "
        WHERE article_id = " . $article_id);

    printResults($result);
}

function printArticleOnDisplay($article_id, $exhibit_id) {
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

    printResults($result);
}

function printArticleInCollection($collection_id, $article_id) {
    $result = executePlainSQL(
        "SELECT col.collection_id, col.name AS collection_name, a.article_id, a.article_name
        FROM collection col, contains ctn, article a  
        WHERE 
            col.collection_id = " . $collection_id . " AND
            col.collection_id = ctn.collection_id AND
            ctn.article_id = a.article_id AND 
            a.article_id = " . $article_id);

    printResults($result);
}

function printResults($result){
    oci_fetch_all($result, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    
    if ($rows) {
        autogenerateTable($rows);
    } else {
        echo "No results found";
    }
}

function autogenerateTable($rows){ 
    echo "<table>";
    echo "<tr>";

    $ncols = count($rows[0]);
    
    foreach ($rows[0] as $columnName => $value) {
        echo "<th>" . convertToTitleCase($columnName) . "</th>";
    }

    echo "</tr>";

    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . $value . "</td>";
        } 
        echo "</tr>";
    }

    echo "</table>";
}

function convertToTitleCase($string) {
    if ($string) {
        // split by delimiter
        $word_arr = explode("_", $string);
        // convert each word to title case
        $word_arr = array_map('strtolower', $word_arr);
        $word_arr = array_map('ucfirst', $word_arr);
        // join array to string
        return implode(" ", $word_arr);
    }
}
?>