<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include( $_SERVER['DOCUMENT_ROOT'].'/core/modules/database/database.php');

class display
{
	public function get_data()
	{
	    $tables = [];
        $database = new database();
        $titles = $database->show_tables();
        foreach ($titles as $key => $value)
        {
            $fields = $database->read($value[0]);
            $columns = $database->get_titles($value[0]);

            $tables[$key]['title'] = $value[0];
            $tables[$key]['columns'] = $columns;
            $tables[$key]['fields'] = $fields;
        }

        return $tables;
	}
}

if(isset($_POST['update'])){
    $database = new database();
    $database->update($_POST['update']['table'], $_POST['update']['column'], $_POST['update']['value'], $_POST['update']['id']);

    echo "OK";
}

if(isset($_POST['delete'])){
    $database = new database();
    $database->delete($_POST['delete']['id'], $_POST['delete']['table']);

    echo "OK";
}

if(isset($_POST['recolor'])){
    $database = new database();
    $database->update($_POST['recolor']['table'], "1. Color", $_POST['recolor']['value'], $_POST['recolor']['id']);

    echo "OK";
}

if(isset($_POST['add'])){
    $table = $_POST['add']['table'];
    $params = [];

    $database = new database();
    $rows = $database->rows_amount($table);

    unset($rows[0]);
    $rows = array_values($rows);

    $rows_amount = count($rows);

    $params[0]["1. Color"] = "#FFFFFF";
    for($i = 1; $i < $rows_amount; $i++)
    {
        $params[0][$rows[$i]] = "";
    }


    $database->write("INSERT", $table, $rows, $params);

    echo "OK";
}
