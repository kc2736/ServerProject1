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

if(isset($_POST['username'])) {
    $id = $_POST['username'];
    unset($_POST['username']);
}
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    unset($_POST['id']);
}
if(isset($_POST['fake'])) unset($_POST['fake']);
unset($_POST['tableName']);
$sql = "UPDATE ".$tableName." ";
$updates = "SET ";
foreach ($_POST as $column => $value){
    if ($value !== '-1') {
        if($updates === "SET "){
            if(in_array($column, $intColumns)){
                $updates .= $column ." = " . $value;
            }else{
                $updates .= $column ." = '" . $value ."'";
            }
        }else{
            if(in_array($column, $intColumns)){
                $updates .= ", " . $column ." = " . $value;
            }else{
                $updates .= ", " . $column ." = '" . $value ."'";
            }
        }
    }
}
$updates .= " ";
if($tableName === 'server_user'){
    $sql .= $updates . " WHERE username = '".$id."'";
}else{
    $sql .= $updates . " WHERE id = ". $id;
}


$con = getDb();
if($con->query($sql) === true){
    header('Location: index.php');
}else{
    echo "Error: " . $sql . "<br>" . $con->error;
}
//var_dump($sql);
$con->close();
