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
$res=$con->query("SELECT * FROM brod_polovno WHERE id_brod_polovno =" . $_GET['id']);
$boat = $res->fetch_assoc();
?>
<head>
    <meta charset="utf-8">
    <title>Used boats - List</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="/js/jquery.min.js"></script>

    <style>
        body{
            padding: 30px;
            padding-top: 10px !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;

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
        /*Drag nad Drop styling*/
        h2 {
            margin: 50px 0;
        }
        section {
            flex-grow: 1;
        }
        .file-drop-area {
            position: relative;
            display: flex;
            align-items: center;
            width: 450px;
            max-width: 100%;
            padding: 25px;
            padding-bottom: 75px;
            border: 1px dashed dimgray;
            margin-top: 50px;
            margin-bottom: 50px;
            border-radius: 3px;
            transition: 0.2s;
        }
        .is-active {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .fake-btn {
            flex-shrink: 0;
            background-color: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            padding: 8px 15px;
            margin-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .file-msg {
            font-size: small;
            font-weight: 300;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            opacity: 0;
        }
        .file-input:focus {
            outline: none;
        }
        footer {
            margin-top: 50px;
        }
        footer a {
            color: rgba(255, 255, 255, 0.4);
            font-weight: 300;
            font-size: 14px;
            text-decoration: none;
        }
        footer a:hover {
            color: white;
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
    <form action="uploadPhotos.php" method="post" enctype="multipart/form-data" id="entry-form">
        <div class="file-drop-area">
            <span class="fake-btn">Choose files</span>
            <span class="file-msg">or drag and drop files here</span>
            <input class="file-input" type="file" name="file[]" multiple>
            <input type="hidden" value="<?=$boat['slika']?>" name="imageUrl">
            <input type="hidden" value="<?=$_GET['id']?>" name="id_brod_polovno">
            <input type="hidden" value="<?=$boat['id_brod_kategorija']?>" name="id_brod_kategorija">
        </div>
        <label for="submit">Submit files</label>
        <input type="submit" name="submit">
    </form>
</div>
<script>
    var $fileInput = $(".file-input");
    var $droparea = $(".file-drop-area");

    // highlight drag area
    $fileInput.on("dragenter focus click", function () {
        $droparea.addClass("is-active");
    });

    // back to normal state
    $fileInput.on("dragleave blur drop", function () {
        $droparea.removeClass("is-active");
    });

    // change inner text
    $fileInput.on("change", function () {
        var filesCount = $(this)[0].files.length;
        var $textContainer = $(this).prev();

        if (filesCount === 1) {
            // if single file is selected, show file name
            var fileName = $(this).val().split("\\").pop();
            $textContainer.text(fileName);
        } else {
            // otherwise show number of files
            $textContainer.text(filesCount + " files selected");
        }
    });
</script>
</body>
