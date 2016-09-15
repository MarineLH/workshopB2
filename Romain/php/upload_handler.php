<?php
require_once "fonctions-bdd.php";
define("UPLOAD_DIR", "/site/wwwroot/uploads/");

if (!empty($_FILES["myFile"])) {
    $myFile = $_FILES["myFile"];
    $ext = pathinfo($myFile, PATHINFO_EXTENSION);
    if($ext == "jpg" or $ext == "jpeg" or $ext == "png") {
        $upload_direction = UPLOAD_DIR . "image/";
    } elseif ($ext == "mp3") {
        $upload_direction = UPLOAD_DIR . "musique/";
    } elseif($ext == "mp4" or $ext == "mpeg") {
        $upload_direction = UPLOAD_DIR . "video/";
    }
    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }

    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists($upload_direction . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }

    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        $upload_direction . $name);
    if (!$success) {
        echo "<p>Unable to save file.</p>";
        exit;
    }

    // set proper permissions on the new file
    chmod($upload_direction . $name, 0744);

    $new_pub = create_publication($_POST['pu_titre'], $_POST['pu_contenu'], $upload_direction . $name, $_POST['pu_id_aut'], $_POST['pu_type_fichier']);

    return $new_pub;
}