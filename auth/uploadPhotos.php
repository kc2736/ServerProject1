<?php
// include composer autoload
include 'database.php';
require '../vendor/autoload.php';
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;
$manager = new ImageManager(array('driver' => 'gd'));
$ogDirectory = explode('/',$_POST['imageUrl']);
$imgUrls = [];
if(substr($_POST['imageUrl'], 0, 2) === '..') {
    $target_dir = $ogDirectory[0] . '/' . $ogDirectory[1] . '/' . $ogDirectory[2] . '/' . $ogDirectory[3] . '/';
}else{
    $target_dir = '/' . $ogDirectory[0] . '/' . $ogDirectory[1] . '/' . $ogDirectory[2] . '/';
}
if (!is_dir($target_dir)) {
    exit("Invalid directory - " . $target_dir);
}
if(isset($_POST['submit'])){
    $countfiles = count($_FILES['file']['name']);
    for($i=0;$i<$countfiles;$i++){

        $uploadOk = 1;
        $target_file = $target_dir . basename($_FILES['file']['name'][$i]);
        echo $target_file;
        // Check if file already exists
        if (file_exists($target_file)) {
            echo '<script>alert("Sorry, one of your files already exists")</script>';
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["file"]["size"][$i] > 500000) {
            echo '<script>alert("Sorry, one of your files is too large.")</script>';
            $uploadOk = 0;
        }
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.")</script>';
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo '<script>alert("Sorry, your files were not uploaded.")</script>';
            // if everything is ok, try to upload file
        } else {
            $img = $manager->make($_FILES["file"]['tmp_name'][$i]);
            $img->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($target_file);

            $img = $manager->make($_FILES["file"]['tmp_name'][$i]);
            $img->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $target_file_ex = pathinfo($target_file);
            $imgUrls[$target_file] = $target_file_ex['dirname'] .'/'. $target_file_ex['filename'] . '-t' .'.'. $target_file_ex['extension'];
            $target_file = $target_file_ex['dirname'] .'/'. $target_file_ex['filename'] . '-t' .'.'. $target_file_ex['extension'];
            $img->save($target_file);
            header('Location: listEntriesView.php?type='.$_POST['id_brod_kategorija']);
        }
    }
    //Add urls to database
    $con = getDB();
    foreach ($imgUrls as $url_velika => $url_mala){
        $con->query("INSERT INTO slika_polovno (id_brod_polovno, url_velika, url_mala) VALUES (".$_POST['id_brod_polovno'].", '$url_velika', '$url_mala')");
    }
}
header('Location: listEntriesView.php?type='.$_POST['id_brod_kategorija']);
