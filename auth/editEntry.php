<?php
include 'database.php';
$con = getDb();

$id = $_POST['id_brod_polovno'];
$item = [
    'naziv' => $_POST['naziv'],
    'opis_hr' => $_POST['opis_hr'],
    'opis_en' => $_POST['opis_en'],
    'opis_de' => $_POST['opis_de'],
    'oprema_hr' => $_POST['oprema_hr'],
    'oprema_en' => $_POST['oprema_en'],
    'oprema_de' => $_POST['oprema_de'],
    'cijena_hr' => $_POST['cijena_hr'],
    'cijena_en' => $_POST['cijena_en'],
    'cijena_de' => $_POST['cijena_de'],
    'lokacija_hr' => $_POST['lokacija_hr'],
    'lokacija_en' => $_POST['lokacija_en'],
    'lokacija_de' => $_POST['lokacija_de'],
    'meta_hr' => $_POST['meta_hr'],
    'meta_en' => $_POST['meta_en'],
    'meta_de' => $_POST['meta_de'],
    'redoslijed' => $_POST['redosljed'],
    'id_brod_kategorija' => $_POST['kategorija']
];

if($_FILES['fileToUpload']['size'] == 0 && $_FILES['fileToUpload']['error'] == 0) {
    $target_dir = "../photos/used-boats/" . $_POST['dirName'];
    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }
    $target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $item['slika'] = $target_file;
    echo $item['slika'];
// Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//RESIZE IMAGE -->
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
//Formating textarea string to add HTML tags
$item['opis_hr'] = '<p>' . nl2br($item['opis_hr']) . '</p>';
$item['opis_en'] = '<p>' . nl2br($item['opis_en']). '</p>';
if(!$item['opis_de']){
    $item['opis_de'] = $item['opis_en'];
}else{
    $item['opis_de'] = '<p>' . nl2br($item['opis_de']). '</p>';
}

$item['oprema_hr'] = '<p>' . nl2br($item['oprema_hr']). '</p>';
$item['oprema_en'] = '<p>' . nl2br($item['oprema_en']). '</p>';
if(!$item['oprema_de']){
    $item['oprema_de'] = $item['oprema_en'];
}else{
    $item['oprema_de'] = '<p>' . nl2br($item['oprema_de']) . '</p>';
}
if(!$item['cijena_de']){
    $item['cijena_de'] = $item['cijena_en'];
}

$stmt = '';
foreach ($item as $key => $value){
    if($key === 'id_brod_kategorija' || $key === 'redoslijed'){
        $stmt .= $key . "=" . $value . ", ";
    }elseif($key === 'slika'){
        if($value != ""){
            $stmt .= $key . "= '" . ($con)->escape_string($value) . "', ";
        }
    }else{
        $stmt .= $key . "= '" . ($con)->real_escape_string($value) . "', ";
    }
}

$stmt = substr($stmt, 0,strlen($stmt)-2);
$sql = ("UPDATE brod_polovno SET " . $stmt . " WHERE id_brod_polovno = " . $id . ";");
if($con->query($sql) === true){
    header('Location: listEntriesView.php?type='.$item['id_brod_kategorija']);
}else{
    echo "Error: " . $sql . "<br>" . $con->error;
}
$con->close();


