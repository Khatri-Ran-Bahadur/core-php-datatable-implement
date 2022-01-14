<?php

/**
 * Database Connection
 */
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "test";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


/**
 * Where query For search
 */
$where = "";
if (!empty($_REQUEST['search']['value'])) {
    $where .= " WHERE  ( email LIKE '" . $_REQUEST['search']['value'] . "%' ";
    $where .= " OR name LIKE '" . $_REQUEST['search']['value'] . "%' )";
}

/**
 * Count Total Record in database
 */
$totalRecordsSql = "SELECT count(*) as total FROM student $where;";
$stmt = $conn->prepare($totalRecordsSql);
$stmt->execute();
$res = $stmt->fetchAll();
$totalRecords = 0;
foreach ($res as $key => $value) {
    $totalRecords = $value['total'];
}


/**
 * Make Column
 */
$columns = array(
    0 => 'id',
    1 => 'name',
    2 => 'email'
);


/**
 * Select query 
 */
$sql = "SELECT id,name,email FROM student $where";

/**
 * This is for ordering
 */
$sql .= " ORDER BY " . $columns[$_REQUEST['order'][0]['column']] . "   " . $_REQUEST['order'][0]['dir'] . "  LIMIT " . $_REQUEST['start'] . " ," . $_REQUEST['length'] . "   ";


/**
 * Fetch only 10 record
 */
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

/**
 * Make datatable Data
 */
$json_data = array(
    "draw"            => intval($_REQUEST['draw']),
    "recordsTotal"    => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data"            => $result   // total data array
);

echo json_encode($json_data);
