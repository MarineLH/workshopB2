<?php
require_once "fonctions-bdd.php";
define("UPLOAD_DIR", "/uploads/");

if (!empty($_FILES["myFile"])) {
    $myFile = $_FILES["myFile"];

    switch ($_POST['pu_type_fichier']) {
        case '1':
            $upload_direction = UPLOAD_DIR . "image/";
            break;
        case '2':
            $upload_direction = UPLOAD_DIR . "video/";
            break;
        case '3':
            $upload_direction = UPLOAD_DIR . "musique/";
            break;
        default:
            $upload_direction = "";
            break;
    }
    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        die("An error occured.");
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
        die("Unable to save file.");
    }

    // set proper permissions on the new file
    chmod($upload_direction . $name, 0744);

    $new_pub = create_publication($_POST['pu_titre'], $_POST['pu_contenu'], $upload_direction . $name, $_POST['pu_id_aut'], $_POST['pu_type_fichier']);

    return $new_pub;
}