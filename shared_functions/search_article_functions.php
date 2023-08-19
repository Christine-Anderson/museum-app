<!--
    Search article functions shared between curator and archivist views
-->

<?php

function handleSearchArticleByNameRequest() {
    global $db_conn;

    $search_term = $_GET['search-term'];

    $result = executePlainSQL(
        "SELECT article_id, article_name, date_aquired, article_condition
        FROM article
        WHERE UPPER(article_name) LIKE '%' || UPPER('" . $search_term . "') || '%'");

    echo '<p>The following articles match ' . $search_term . ':</p>';
    printResults($result);
}

function handleViewAllArticlesRequest() {
    global $db_conn;

    $result = executePlainSQL(
        "SELECT article_id, article_name, date_aquired, article_condition
        FROM article");

    echo '<p>The following articles are currently located in the museum:</p>';
    printResults($result);
}

?>