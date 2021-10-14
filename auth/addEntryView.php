<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /auth/login');
    exit;
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Used boats - New</title>
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
        .content {
            width: 1000px;
            margin: 0 auto;
        }

        .textarea{
            height: 120px;
            width: 250px;
        }
        .text-input{
            height: 40px;
            width: 250px;
            vertical-align: center;
            white-space: normal;
            margin: 10px;
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
    <h2>New entry</h2>
    <form action="addEntry.php" method="post" enctype="multipart/form-data" id="entry-form">
        <!--NAME-->
        <label for="naziv">Naziv:</label>
        <input class="text-input" name="naziv" type="text" required><br>
        <hr>
        <!--CATEGORY-->
        <label for="kategorija">Kategorija brodice:</label>
        <select name="kategorija" id="boat-type" required>
            <option value="5">Motorna plovila</option>
            <option value="6">Jedrilice</option>
            <option value="7">Hypo Group Alpe Adria</option>
        </select>
        <hr>
        <!--IMAGE UPLOAD-->
        <label for="fileToUpload">Image:</label>
        <input style="padding-bottom: 10px" type="file" name="fileToUpload" id="fileToUpload" required><br>

        <label for="dirName">Directory name:</label>
        <input type="text" name="dirName" required><br>
        <hr>
        <!--DESCRIPTIONS-->
        <label for="opis_hr">Opis (HR):</label>
        <textarea class="textarea" name="opis_hr" id="opis_hr" cols="30" rows="10" required></textarea>
        <br>

        <label for="opis_en">Opis (EN):</label>
        <textarea class="textarea" name="opis_en" id="opis_en" cols="30" rows="10" required></textarea>
        <br>

        <label for="opis_de">Opis (DE):</label>
        <textarea class="textarea" name="opis_de" id="opis_de" cols="30" rows="10"></textarea>
        <br>
        <hr>
        <!--EQUIPMENT-->
        <label for="oprema_hr">Oprema (HR):</label>
        <textarea class="textarea" name="oprema_hr" id="oprema_hr" cols="30" rows="10" required></textarea>
        <br>

        <label for="oprema_en">Oprema (EN):</label>
        <textarea class="textarea" name="oprema_en" id="oprema_en" cols="30" rows="10" required></textarea>
        <br>

        <label for="oprema_de">Oprema (DE):</label>
        <textarea class="textarea" name="oprema_de" id="oprema_de" cols="30" rows="10"></textarea>
        <br>
        <hr>
        <!--PRICE-->
        <label for="cijena_hr">Cijena (HR):</label>
        <input class="text-input" name="cijena_hr" type="text" required><br>

        <label for="cijena_en">Cijena (EN):</label>
        <input class="text-input" name="cijena_en" type="text" required><br>

        <label for="cijena_de">Cijena (DE):</label>
        <input class="text-input" name="cijena_de" type="text"><br>
        <hr>
        <!--LOCATION-->
        <label for="lokacija_hr">Lokacija (HR):</label>
        <input class="text-input" name="lokacija_hr" type="text" required><br>

        <label for="lokacija_en">Lokacija (EN):</label>
        <input class="text-input" name="lokacija_en" type="text" required><br>

        <label for="lokacija_de">Lokacija (DE):</label>
        <input class="text-input" name="lokacija_de" type="text"><br>
        <hr>
        <!--META-->
        <label for="meta_hr">Meta (HR):</label>
        <input class="text-input" name="meta_hr" type="text"><br>

        <label for="meta_en">Meta (EN):</label>
        <input class="text-input" name="meta_en" type="text"><br>

        <label for="meta_de">Meta (DE):</label>
        <input class="text-input" name="meta_de" type="text"><br>
        <hr>
        <!--ORDER-->
        <label for="redosljed">Redosljed brodova:</label>
        <input class="text-input" name="redosljed" type="text" required><br>
        <hr>

        <input type="submit" name="submit" value="Submit form">
    </form>
</div>

</body>
</html>
