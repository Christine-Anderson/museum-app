<!--
    Print functions for outputting database search results to html table
-->

<?php

function printResults($result){ 
	if (oci_num_fields($result) > 0) {
        autogenerateTable($result);
    } else {
        echo "No results found";
    }
}

function autogenerateTable($result){ 
	global $db_conn;
	echo "<table>";
	echo "<tr>";

	$ncols = oci_num_fields($result);

    for ($i = 1; $i <= $ncols; $i++) {
        echo "<th>" . convertToTitleCase(oci_field_name($result, $i)) . "</th>";
    }

    echo "</tr>";

    while ($row = oci_fetch_assoc($result)) {
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