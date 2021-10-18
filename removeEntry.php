<?php
require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /login.php');
    exit;
}
$con = getDb();
if ($_POST['tableName'] === 'server_user'){
    $sql = "DELETE FROM ".$_POST['tableName']." WHERE ".$_POST['key']." = '".$_POST['id']."'";
}else {
    $sql = "DELETE FROM " . $_POST['tableName'] . " WHERE " . $_POST['key'] . " = " . $_POST['id'];
}
$res = $con->query($sql);
$con->close();

var_dump($sql);
header('Location: /index.php');