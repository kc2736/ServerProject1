<?php
// include composer autoload
include 'database.php';
require '../vendor/autoload.php';
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;
$manager = new ImageManager(array('driver' => 'gd'));
$con = getDb();
$item = [
    'naziv' => $_POST['naziv'],
    'slika' => "",
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

$target_dir = "../photos/used-boats/" . $_POST['dirName'];
if (!is_dir($target_dir)) {
    mkdir($target_dir);
}
$target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$item['slika'] = $target_file;
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    $img = $manager->make($_FILES['fileToUpload']['tmp_name']);
    $img->resize(1000, null, function ($constraint) {
        $constraint->aspectRatio();
//        $constraint->upscale();
    });
    $img->save($target_file);
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

$keys = '';
$values = '';
foreach ($item as $key => $value){
    $keys .= $key . ",";
    if($key === 'id_brod_kategorija' || $key === 'redoslijed'){
        if($value === null){
            $value = 5;
        }
        $values .= $value . ",";
    }else{
        $values .= "'" . ($con)->escape_string($value) . "',";
    }
}
$values = substr($values, 0,strlen($values)-1);
$keys = substr($keys, 0,strlen($keys)-1);
$sql = ("INSERT INTO brod_polovno (".$keys.") VALUES(".$values.")");
if($con->query($sql) === true){
    header('Location: addEntryView.php');
}else{
    echo "Error: " . $sql . "<br>" . $con->error;
}
$con->close();


