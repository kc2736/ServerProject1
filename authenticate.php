<?php
include 'database.php';
session_start();

$con = getDb();
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.

    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username']) && !isset($_POST['password']) ) {
//    var_dump("test");

    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT role , password, team, league FROM server_user WHERE username = ?')) {


    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($role, $password, $team, $league);
        $stmt->fetch();
        // Account exists, now we verify the password.
        if (hash('SHA256', $_POST['password']) === $password) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['role'] = $role;
            $_SESSION['team'] = $team;
            $_SESSION['league'] = $league;
            header('Location: controlPanel.php');
            exit;
        } else {
            // Incorrect password
            echo 'Incorrect username and/or password!';
        }
    } else {
        // Incorrect username
        echo 'Incorrect username and/or password!';
    }

    $stmt->close();

}
?>