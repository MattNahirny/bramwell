<?php

$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 

$target_dir = "uploads/report" . $_POST["reportID"] . "/";
if(!(is_dir($target_dir))){
	mkdir($target_dir);
}
$test = $_FILES['fileToUpload'];
$target_file = $target_dir . basename($test["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($test["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "File already exists.";
    $uploadOk = 0;
}

// Allow only jpg, png, gif and jpeg
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files can be uploaded.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";

// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($test["tmp_name"], $target_file)) {
        echo "The file ". basename( $test["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>