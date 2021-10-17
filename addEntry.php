<?php
require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /login.php');
    exit;
}
$intColumns = array('id','jerseynumber','sport','league','season','team','player','position','hometeam', 'awayteam','homescore', 'awayscore','completed','year','maxplayers','role');
$tableName = $_POST['tableName'];
unset($_POST['tableName']);
$sql = "INSERT INTO ".$tableName." ";
$columns = "(";
$values = "(";
foreach ($_POST as $column => $value){
    if ($value !== '-1') {
        if ($columns === "(") {
            $columns .= $column;
        } else {
            $columns .= ", " . $column;
        }
        if ($values === "(") {
            if (in_array($column, $intColumns)) {
                $values .= $value;
            } else {
                $values .= "'" . $value . "'";
            }
        } else {
            if (in_array($column, $intColumns)) {
                if ($column === 'password') {
                    $values .= ", " . hash('SHA256', $value);
                } else {
                    $values .= ", " . $value;
                }
            } else {
                $values .= ", '" . $value . "'";
            }
        }
    }
}
$columns .= ")";
$values .= ");";
$sql .= $columns . " VALUES" . $values;

$con = getDb();
if($con->query($sql) === true){
    header('Location: index.php');
}else{
    echo "Error: " . $sql . "<br>" . $con->error;
}
//var_dump($sql);
$con->close();