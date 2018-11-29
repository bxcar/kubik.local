<?php
//$namefile = $_FILES["croppedImage"]["name"];
//$newfilename = $namefile;

/*foreach (new DirectoryIterator(dirname(__FILE__) . "/photo") as $file) {
    if ($file->isFile()) {

        if ($file->getFilename() == $namefile.'.png') {
            $newfilename = $namefile . '1';
        } else {

        }
    }
}*/

$namefile = rand(1, 9999999);
$newnamefile = $namefile;

foreach (new DirectoryIterator(dirname(__FILE__) . "/photo") as $file) {
    if ($file->isFile()) {

        if ($file->getFilename() == $namefile.'.png') {
            $newnamefile = $namefile . '1';
        } else {

        }
    }
}

move_uploaded_file($_FILES["croppedImage"]["tmp_name"], "photo/" . $newnamefile . ".png");

echo json_encode($newnamefile);