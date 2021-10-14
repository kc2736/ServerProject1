<?php
// We need to use sessions, so you should always start sessions using the below code.
include 'database.php';

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /auth/login');
    exit;
}
$con = getDb();
// Try and connect using the info above.
$sql = ("UPDATE brod_polovno SET aktivan = ".$_GET['status']." WHERE id_brod_polovno = ". $_GET['id']);
if($con->query($sql) === true){
    echo "Entry updated";
    header('Location: listEntriesView.php?type='.$_GET['type']);
}else{
    echo "Error: " . $sql . "<br>" . $con->error;
}
$con->close();