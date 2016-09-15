<?php
$host = "br-cdbr-azure-south-b.cloudapp.net";
$dbname = "bdd_workshopb2";
$user = "b95cdda679d7bb";
$pass = "d009f4e7";

/*
// localhost:
$host = "localhost";
$dbname = "localgenhipster";
$user = "root";
$pass = "toor";
 */

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
////////////////////////////
// VERIFICATIONS
function verif_mail($mail) {
    global $bdd;
    $q = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE ut_adressemail = :mail");
    $q->execute(array(
        ':mail' => $mail
    ));
    $mail = $q->fetchColumn();

    if($mail == "0") {
        return true; // Email libre
    } else {
        return false; // Email pris
    }
}

function verif_fb($id_fb) {
    global $bdd;
    $q = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE ut_id_fb = :id_fb");
    $q->execute(array(
        ':id_fb' => $id_fb
    ));
    $user_exists = $q->fetchColumn();

    if($user_exists == "0") {
        return true; // utilisateur n'existe pas
    } else {
        return false; // utilisateur existe
    }
}

function verif_tw($id_tw) {
    global $bdd;
    $q = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE ut_id_tw = :id_tw");
    $q->execute(array(
        ':id_tw' => $id_tw
    ));
    $user_exists = $q->fetchColumn();

    if($user_exists == "0") {
        return true; // utilisateur n'existe pas
    } else {
        return false; // utilisateur existe
    }
}

function is_modo_or_admin($uti_id) {
    global $bdd;
    $q = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE ut_id = :ut_id AND (ut_type_utilisateur = 3 OR ut_type_utilisateur = 4)");
    $q->execute();
    $is_modo = $q->fetchColumn();

    if($is_modo == "0") {
        return true;
    } else {
        return false;
    }
}
////////////////////////////
// UTILISATEUR
function get_users() {
    global $bdd;
    $q = $bdd->prepare("SELECT ut_prenomnom, ut_adressemail FROM utilisateur");
    $q->execute();

    return $q->fetch(PDO::FETCH_ASSOC);
}

function get_user($user_mail, $user_pwd) {
    global $bdd;
    $user_pwd = hash("sha256", $user_pwd);

    $q = $bdd->prepare("SELECT ut_id, ut_id_fb, ut_id_tw, ut_prenomnom, ut_desc, ut_type_utilisateur FROM utilisateur WHERE ut_adressemail = :user_mail AND ut_mdp = :user_pwd");
    $q->execute(array(
        ':user_mail' => $user_mail,
        ':user_pwd' => $user_pwd
    ));

    return $q->fetch(PDO::FETCH_ASSOC);
}

function create_user_classic($prenomnom, $mail, $pwd) {
    global $bdd;
    $pwd = hash("sha256", $pwd);
    if(verif_mail($mail)) {
        $q = $bdd->prepare("INSERT INTO utilisateur (ut_prenomnom, ut_adressemail, ut_mdp, ut_type_utilisateur) VALUES (:prenomnom, :mail, :pwd, 1)");
        $q->execute(array(
            ':prenomnom' => $prenomnom,
            ':mail' => $mail,
            ':pwd' => $pwd
        ));
        return 'success';
    } else {
        return 'L\'email est déjà pris.';
    }
}

function create_user_fb($prenomnom, $id_fb) {
    global $bdd;
    if(verif_fb($id_fb)) {
        $q = $bdd->prepare("INSERT INTO utilisateur (ut_prenomnom, ut_type_utilisateur, ut_id_fb) VALUES (:prenomnom, 1, :id_fb)");
        $q->execute(array(
            ':prenomnom' => $prenomnom,
            ':id_fb' => $id_fb
        ));
    }
    $q = $bdd->prepare("SELECT ut_id, ut_id_fb, ut_id_tw, ut_prenomnom, ut_desc, ut_type_utilisateur FROM utilisateur WHERE ut_id_fb = :id_fb");
    $q->execute(array(
        ':id_fb' => $id_fb
    ));

    return $q->fetch(PDO::FETCH_ASSOC);
}

function create_user_tw($prenomnom, $id_tw) {
    global $bdd;
    if (verif_tw($id_tw)) {
        $q = $bdd->prepare("INSERT INTO utilisateur(ut_prenomnom, ut_type_utilisateur, ut_id_tw) VALUES(:prenomnom, 1, :id_tw)");
        $q->execute(array(
            ':prenomnom' => $prenomnom,
            ':id_tw' => $id_tw
        ));
    }
    $q = $bdd->prepare("SELECT ut_id, ut_id_fb, ut_id_tw, ut_prenomnom, ut_desc, ut_type_utilisateur FROM utilisateur WHERE ut_id_tw = :id_tw");
    $q->execute(array(
        ':id_tw' => $id_tw
    ));

    return $q->fetch(PDO::FETCH_ASSOC);
}

function update_description($ut_id, $desc) {
    global $bdd;
    $q = $bdd->prepare("UPDATE utilisateur SET ut_desc = :desc WHERE ut_id = :ut_id");
    $q->execute(array(
        'desc' => $desc,
        'ut_id' => $ut_id
    ));
    return 'success';
}
////////////////////////////
// AVIS
function like($ut_id, $pu_id) {
    global $bdd;
    $v = $bdd->prepare("SELECT COUNT(*) FROM avis WHERE ut_id = :ut_id AND pu_id = :pu_id");
    $v->execute(array(
        ':ut_id' => $ut_id,
        ':pu_id' => $pu_id
    ));
    $exists = $v->fetchColumn();

    if($exists == "0") {
        $q = $bdd->prepare("INSERT INTO avis (ut_id, pu_id, ut_like) VALUES (:ut_id, :pu_id, 1)");
        $q->execute(array(
            ':ut_id' => $ut_id,
            ':pu_id' => $pu_id
        ));
    } else {
        $q = $bdd->prepare("UPDATE avis SET ut_like = 1 WHERE ut_id = :ut_id AND pu_id = :pu_id");
        $q->execute(array(
            ':ut_id' => $ut_id,
            ':pu_id' => $pu_id
        ));
    }
    return 'success';
}

function dislike($ut_id, $pu_id) {
    global $bdd;
    $v = $bdd->prepare("SELECT COUNT(*) FROM avis WHERE ut_id = :ut_id AND pu_id = :pu_id");
    $v->execute(array(
        ':ut_id' => $ut_id,
        ':pu_id' => $pu_id
    ));
    $exists = $v->fetchColumn();

    if($exists == "0") {
        $q = $bdd->prepare("INSERT INTO avis (ut_id, pu_id, ut_like) VALUES (:ut_id, :pu_id, 0)");
        $q->execute(array(
            ':ut_id' => $ut_id,
            ':pu_id' => $pu_id
        ));
    } else {
        $q = $bdd->prepare("UPDATE avis SET ut_like = 0 WHERE ut_id = :ut_id AND pu_id = :pu_id");
        $q->execute(array(
            ':ut_id' => $ut_id,
            ':pu_id' => $pu_id
        ));
    }
    return 'success';
}

function get_likes_dislikes($pu_id) {
    global $bdd;
    $q = $bdd->prepare("SELECT pu_id AS NumPub, 'Likes' AS Avis, COUNT(*) AS Nb FROM avis WHERE pu_id = :pu_id AND ut_like = 1 " .
                        "UNION ALL SELECT pu_id AS NumPub, 'Dislikes' AS Avis, COUNT(*) As Nb FROM avis WHERE pu_id = :pu_id AND ut_like = 0");
    $q->execute(array(
        ':pu_id' => $pu_id
    ));
    $likes_dislikes = $q->fetchAll(PDO::FETCH_ASSOC);

    $simple_l_d['NumPublication'] = $likes_dislikes[0]['NumPub'];
    $simple_l_d['Likes'] = $likes_dislikes[0]['Nb'];
    $simple_l_d['Dislikes'] = $likes_dislikes[1]['Nb'];
    return $simple_l_d;
}
////////////////////////////
// PUBLICATIONS
function get_latest_publications() {
    global $bdd;
    $q = $bdd->prepare("SELECT pu_titre, pu_date, pu_contenu, pu_dirfichier, ut_prenomnom, pu_type_fichier " .
                        "FROM publication INNER JOIN utilisateur ON pu_uti_auteur = ut_id " .
                        "WHERE pu_valider = 1 ORDER BY pu_date DESC LIMIT 6");
    $q->execute();

    $publications = $q->fetchAll(PDO::FETCH_ASSOC);

    return $publications;
}

function get_publications() {
    global $bdd;
    $q = $bdd->prepare("SELECT pu_id, pu_titre, pu_date, pu_contenu, pu_dirfichier, ut_prenomnom, pu_type_fichier " .
                        "FROM publication INNER JOIN utilisateur ON pu_uti_auteur = ut_id " .
                        "WHERE pu_valider = 1 ORDER BY pu_date DESC");
    try {
        $q->execute();
        $publications = $q->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die($e->getMessage());
    }

    return $publications;
}

function get_user_publications($ut_id) {
    global $bdd;
    $q = $bdd->prepare("SELECT pu_id, pu_titre, pu_date, pu_contenu, pu_dirfichier, pu_type_fichier " .
        "FROM publication INNER JOIN utilisateur ON pu_uti_auteur = ut_id " .
        "WHERE pu_valider = 1 AND pu_uti_auteur = :id_aut ORDER BY pu_date DESC");
    $q->execute(array(
        ':id_aut' => $ut_id
    ));

    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function get_one_publication($pu_id) {
    global $bdd;
    $q = $bdd->prepare("SELECT pu_id, pu_titre, pu_date, pu_contenu, pu_dirfichier, pu_type_fichier " .
        "FROM publication INNER JOIN utilisateur ON pu_uti_auteur = ut_id " .
        "WHERE pu_valider = 1 AND pu_id = :pu_id");
    $q->execute(array(
        ':pu_id' => $pu_id
    ));

    return $q->fetch(PDO::FETCH_ASSOC);
}


function create_publication($titre, $contenu, $dirfichier, $id_auteur, $type_fichier) {
    global $bdd;
    $q = $bdd->prepare("INSERT INTO publication (pu_titre, pu_contenu, pu_dirfichier, pu_uti_auteur, pu_type_fichier, pu_valider, pu_date) " .
                        "VALUES (:titre, :contenu, :dirfichier, :id_auteur, :type_fichier, 0, DATE_ADD(NOW(), INTERVAL 2 HOUR))");
    try {
        $q->execute(array(
            ':titre' => $titre,
            ':contenu' => $contenu,
            ':dirfichier' => $dirfichier,
            ':id_auteur' => $id_auteur,
            ':type_fichier' => $type_fichier
        ));
        return 'success';
    } catch (Exception $e) {
        die($e->getMessage());
    }

}

function validate_publication($pu_id, $modo_id) {
    global $bdd;
    if (is_modo_or_admin($modo_id)) {
        $q = $bdd->prepare("UPDATE publication SET pu_valider = 1, pu_modo_valideur = :modo_id WHERE pu_id = :pu_id");
        $q->execute(array(
            ':modo_id' => $modo_id,
            ':pu_id' => $pu_id
        ));
        return 'success';
    } else {
        return "Vous n'êtes pas administrateur !";
    }
}

function del_publication($pu_id) {
    //todo:supprimer les likes/dislikes, les comentaires, les signalements, puis la publi
}

// *****************************************
// FAVORIS
function add_favorite($ut_id, $ut_favori) {
    //todo
}

function del_favorite($ut_id, $ut_favori) {
    //todo
}

