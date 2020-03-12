<?php
require_once "database.php";

function check_exists($col, $table, $entry)
{
    $db = new database();
    if (!is_array($col) and !is_array($entry))
        $qq = "$col='$entry'";
    else
    {
        $query_arr = [];
        for ($i = 0; $i < count($col); $i++)
            $query_arr[] = "$col[$i]='$entry[$i]'";
        $qq = join(" AND ", $query_arr);
    }
    if (is_array($col))
        $col = join(', ', $col);
    $query = "SELECT $col FROM $table WHERE $qq";
    return($db->db_check($query));
}
?>