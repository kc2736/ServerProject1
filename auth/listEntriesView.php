<?php
include 'database.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /auth/login');
    exit;
}

// Try and connect using the info above.
$con = getDb();
$buttonText = '';
$status = 1;
$res = null;
if($_GET['type'] == 0) {
    $res = $con->query("SELECT * FROM brod_polovno WHERE aktivan = " . $_GET['type'] . " ORDER BY redoslijed ASC");
    $buttonText = 'Mark as active';
    $status = 1;
}else {
    $res = $con->query("SELECT * FROM brod_polovno WHERE (aktivan = 1 AND id_brod_kategorija = " . $_GET['type'] . ") ORDER BY redoslijed ASC");
    $buttonText = 'Mark as inactive';
    $status = 0;
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Used boats - List</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <style>
        body{
            padding: 30px;
            padding-top: 10px !important;
        }
        label{
            padding: 20px;
            vertical-align: center;
            vert-align: middle;
        }
        form{
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            justify-content: center;
        }

        .navtop {
            background-color: #2f3947;
            height: 60px;
            width: 100%;
            border: 0;
        }
        .navtop div {
            display: flex;
            margin: 0 auto;
            width: 1000px;
            height: 100%;
        }

        .navtop div h1 {
            flex: 1;
            font-size: 24px;
            padding: 0;
            margin: 0;
            color: #eaebed;
            font-weight: normal;
            display: inline-flex;
            align-items: center;
        }
        .navtop div a {
            padding: 0 20px;
            text-decoration: none;
            color: #c1c4c8;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }
        .navtop div a i {
            padding: 2px 8px 0 0;
        }
        .navtop div a:hover {
            color: #eaebed;
        }
        body.loggedin {
            background-color: #f3f4f7;
        }
        h2{text-align: center;}
        .item-container{
            border: 5px solid darkgray;
            justify-content: center;
            text-align: center;
            max-width: 600px;
            margin: auto;
        }

    </style>
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Content manager</h1>
        <a href="listEntriesView.php?type=0">Inactive boats</a>
        <a href="listEntriesView.php?type=5">Motor boats</a>
        <a href="listEntriesView.php?type=6">Sailboats</a>
        <a href="addEntryView.php">Add entry</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <form action="editEntryView.php" method="POST" id="entry-form">
        <input type="hidden" name='boat_id' id="boat_id">
    </form>
    <?php while($row=$res->fetch_assoc()) {
        ?>
        <div class="item-container" id="item-<?=$row['id_brod_polovno']?>">
            <h2><?= $row['naziv']?></h2>
            <p><?= $row['opis_hr']?></p>
            <button style="min-width: 200px; margin-bottom: 10px" type="button" onclick="openEditor(<?= $row['id_brod_polovno']?>)">Edit</button>
            <button style="min-width: 200px; margin-bottom: 10px" type="button" onclick="window.location.href='uploadView.php?id=<?= $row['id_brod_polovno']?>&type=<?=$_GET['type']?>'">Add images</button>
            <button style="min-width: 200px; margin-bottom: 10px" type="button" onclick="window.location.href='deactivateEntry.php?id=<?= $row['id_brod_polovno']?>&type=<?=$_GET['type']?>&status=<?=$status?>'"><?=$buttonText?></button>
        </div>
        <?php
    }
    ?>
</div>
<script>
    function openEditor(row){
        console.log(row)
        document.getElementById('boat_id').value = row;
        document.getElementById('entry-form').submit();
    }
</script>
</body>