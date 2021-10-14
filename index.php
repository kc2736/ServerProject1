<?php
session_start();
// If the user is not logged in redirect to the login page...
if (isset($_SESSION['loggedin'])) {
    header('Location: /controlPanel.php');
    exit;
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/app.css">
    <title>Project 1</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
    <h1>Server Programming - Project 1</h1>
    <div class="login">
        <h1>Login</h1>
        <form action="../authenticate.php" method="post">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <input type="submit" value="Login">
        </form>
    </div>

</body>
</html>