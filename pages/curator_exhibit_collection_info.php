<!--
    Curator Exhibit and Collection page 
    Curator can view exhibits, associated activites, and collections
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibit and Collection Info</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php
    include 'curator_sidebar.php'
    ?>

    <div class="content">
    <h2>Exhibit and Collection Info</h2>

    <h3>Search Exhibits and Activities</h3>
        <form method="GET" id="curator-search-exhibit-or-activity-request" action="curator_exhibit_collection_info.php">
            <input type="hidden" id="curator-search-exhibit-or-activity-request" name="curator-search-exhibit-or-activity-request">
            <label for="search">Find:</label>
				<select name="search-option" id="search">
					<option value="exhibit-search">Exhibits</option>
					<option value="activity-search">Activities</option>
				</select>
			<br/><br/>
            <input type="submit" value="Search" name="submit-search-exhibit-or-activity">
            &nbsp;
            <input type="submit" value="View All" name="submit-view-all-exhibit-or-activity"></p>
            <br/>
        </form>
    
        <h3>Find Articles on Display</h3>
        <form method="GET" id="find-article-on-display-request" action="curator_exhibit_collection_info.php">
            <input type="hidden" id="find-article-on-display-request" name="find-article-on-display-request">
            exhibit ID: <input type="number" name="exhibit-id-find-article" min="1000" max="9999" required>
            <input type="submit" value="Find Articles" name="submit-find-article-on-display"></p>
            </br>
        </form>

        <h3>Search Collections</h3>
        <form method="GET" id="collection-search-by-name-request" action="curator_exhibit_collection_info.php">
            <input type="hidden" id="collection-search-by-name-request" name="collection-search-by-name-request">
            <input type="text" name="search-term">
            <input type="submit" value="Search Collections" name="submit-collection-search-by-name">
            &nbsp;
            <input type="submit" value="View All" name="submit-view-all-collections"></p>
            <br/>
        </form>

        <?php

        include '../shared_functions/database_functions.php';
        include '../shared_functions/print_functions.php';

        function handleDatabaseRequest($request_method) {
            if (connectToDB()) {
                if (array_key_exists('submit-search-exhibit-or-activity', $request_method)) {
                    handleSearchExhibitOrActivityRequest();
                } else if (array_key_exists('submit-view-all-exhibit-or-activity', $request_method)) {
                    handleAllExhibitOrActivityRequest();   
                } else if (array_key_exists('submit-exhibit-search', $request_method)) {
                    handleSearchExhibitRequest();
                } else if (array_key_exists('submit-activity-search', $request_method)) {
                    handleSearchActivityRequest();
                }else if (array_key_exists('submit-find-article-on-display', $request_method)) {
                    handleFindArticlesOnDisplayRequest();
                } else if (array_key_exists('submit-collection-search-by-name', $request_method)) {
                    handleSearchCollectionByNameRequest();
                } else if (array_key_exists('submit-view-all-collections', $request_method)) {
                    handleViewAllCollectionsRequest();
                }
                disconnectFromDB();
            }
        }

        function handleSearchExhibitOrActivityRequest() {
            global $db_conn;
            $dropdown_value = $_GET['search-option'];

            if ($dropdown_value == 'exhibit-search') {
                renderExhibitSearchForm();
            } else if ($dropdown_value == 'activity-search') {
                renderActivitySearchForm();
            } else {
                echo "Please select a search option.";
            }
        }

        function handleAllExhibitOrActivityRequest() {
            global $db_conn;
            $dropdown_value = $_GET['search-option'];

            if ($dropdown_value == 'exhibit-search') {
                viewAllExhibits();
            } else if ($dropdown_value == 'activity-search') {
                viewAllActivites();
            } else {
                echo "Please select a search option.";
            }
        }

        function viewAllExhibits() {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT exhibit_id, exhibit_name, start_date, end_date
                FROM exhibit");

            echo '<p>The following exhibits are currently running:</p>';
            printResults($result);
        }

        function viewAllActivites() {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT e.exhibit_id, e.exhibit_name, a.name, a.schedule
                FROM exhibit e, activities a
                WHERE e.exhibit_id = a.exhibit_id");

            echo '<p>The following activities are currently running for each exhibit:</p>';
            printResults($result);
        }

        function handleSearchExhibitRequest() {
            global $db_conn;

            $search_option = "exhibits";
            $table = "exhibit";
            $columns = "exhibit_id, exhibit_name, start_date, end_date";
            $exhibit_name = $_GET['exhibit-name'];
            $start_date = $_GET['start-date'];
            $end_date = $_GET['end-date'];

            $values = [$exhibit_name, $start_date, $end_date];
            $conditions = [];

            if (!empty($exhibit_name)) {
                $conditions[] = "UPPER(exhibit_name) LIKE '%' || UPPER('" . $exhibit_name . "') || '%'";
            }

            if (!empty($start_date)) {
                $conditions[] = "start_date > TO_DATE('" . $start_date . "', 'YYYY-MM-DD')";
            }

            if (!empty($end_date)) {
                $conditions[] = "end_date < TO_DATE('" . $end_date . "', 'YYYY-MM-DD')";
            }

            if (!empty($conditions)) {
                $query = implode(' AND ', $conditions);
                searchTableGivenQuery($search_option, $columns, $table, $query);
            } else {
                echo 'Please enter a search condition.';
            }
        }

        function handleSearchActivityRequest() {
            global $db_conn;

            $search_option = "activities";
            $table = "exhibit e, activities a";
            $columns = "e.exhibit_id, e.exhibit_name, a.name, a.schedule";
            $activity_name = $_GET['activity-name'];
            $day_of_the_week = $_GET['day-of-the-week'];

            $values = [$activity_name, $day_of_the_week];
            $conditions = [];

            if (!empty($activity_name)) {
                $conditions[] = "UPPER(a.name) LIKE '%' || UPPER('" . $activity_name . "') || '%'";
            }

            if (!empty($day_of_the_week)) {
                $conditions[] = "UPPER(a.schedule) LIKE '%' || UPPER('" . $day_of_the_week . "') || '%'";
            }

            if (!empty($conditions)) {
                $conditions[] = "e.exhibit_id = a.exhibit_id";
                $query = implode(' AND ', $conditions);
                searchTableGivenQuery($search_option, $columns, $table, $query);
            } else {
                echo 'Please enter a search condition.';
            }
        }

        function searchTableGivenQuery($search_option, $columns, $table, $query) {
            $result = executePlainSQL(
                "SELECT ". $columns ."
                FROM " . $table . "
                WHERE " . $query);
    
            echo '<p>The following ' . $search_option . ' match the given conditions:</p>';
            printResults($result);
        }

        function handleFindArticlesOnDisplayRequest() {
            global $db_conn;

            $exhibit_id = $_GET['exhibit-id-find-article'];

            $result = executePlainSQL(
                "SELECT 
                    e.exhibit_id, e.exhibit_name,
                    a.article_id, a.article_name, a.storage_location
                FROM exhibit e, displays d, article a
                WHERE
                    e.exhibit_id = " . $exhibit_id . " AND
                    e.exhibit_id = d.exhibit_id AND
                    d.article_id = a.article_id");

            echo '<p>The following articles are currently on display:</p>';
            printResults($result);
        }

        function handleCollectionInfoRequest() {
            global $db_conn;

            $result = executePlainSQL(
            "SELECT c.collection_id, c.name, a.article_id, a. article_name , c.sin
            FROM collection c, article a, contains cont
            WHERE c.collection_id = cont.collection_id
            AND a.article_id = cont.article_id
            ORDER BY c.collection_id");

            echo '<br/><br/>';
            echo 'All Collections:';
            printResults($result);
        }

        function handleSearchCollectionByNameRequest() {
            global $db_conn;

            $search_term = $_GET['search-term'];

            $result = executePlainSQL(
                "SELECT col.collection_id, col.name AS collection_name, a.article_id, a.article_name, a.storage_location
                FROM collection col, contains ctn, article a  
                WHERE 
                    UPPER(col.name) LIKE '%' || UPPER('" . $search_term . "') || '%' AND
                    col.collection_id = ctn.collection_id AND
                    ctn.article_id = a.article_id");

            echo '<p>The following collections match ' . $search_term . ':</p>';
            printResults($result);
        }

        function handleViewAllCollectionsRequest() {
            global $db_conn;

            $result = executePlainSQL(
                "SELECT col.collection_id, col.name AS collection_name, a.article_id, a.article_name, a.storage_location
                FROM collection col, contains ctn, article a  
                WHERE 
                    col.collection_id = ctn.collection_id AND
                    ctn.article_id = a.article_id");

            echo '<p>All collections:</p>';
            printResults($result);
        }

        function renderExhibitSearchForm() {
            echo '<p>Please enter the the search conditions:</p>';
            echo '<form method="GET" id="curator-search-exhibit-condition">';
            echo '<input type="hidden" id="curator-search-exhibit-request" name="curator-search-exhibit-request">';
            echo 'Name <input type="text" name="exhibit-name">  <br/><br/>';
            echo 'Starts After <input type="date" name="start-date">  <br/><br/>';
            echo 'Ends Before <input type="date" name="end-date"> <br/><br/>';
            echo '<input type="submit" value="Search" name="submit-exhibit-search"></p>';
            echo '</form>';
        }

        function renderActivitySearchForm() {
            echo '<p>Please enter the the search conditions:</p>';
            echo '<form method="GET" id="curator-search-activity-condition">';
            echo '<input type="hidden" id="curator-search-activity-request" name="curator-search-activity-request">';
            echo 'Name <input type="text" name="activity-name">  <br/><br/>';
            echo 'Day of the week <input type="text" name="day-of-the-week">  <br/><br/>';
            echo '<input type="submit" value="Search" name="submit-activity-search"></p>';
            echo '</form>';
        }

        if (isset($_GET['submit-search-exhibit-or-activity']) || isset($_GET['submit-view-all-exhibit-or-activity']) || isset($_GET['submit-exhibit-search'])
            || isset($_GET['submit-activity-search']) || isset($_GET['submit-find-article-on-display']) || isset($_GET['collection-search-by-name-request'])) {
            handleDatabaseRequest($_GET);
        }

        ?>
    </div> 
</body>
</html>