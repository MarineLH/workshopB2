<?php
$host = "br-cdbr-azure-south-b.cloudapp.net";
$dbname = "bdd_workshopb2";
$user = "b95cdda679d7bb";
$pass = "d009f4e7";

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

function get_users() {
    global $bdd;
    $q = $bdd->prepare("select ut_nom, ut_prenom, ut_adressemail FROM utilisateur");
    $q->execute();

    $users = $q->fetchAll(pdo::FETCH_ASSOC);

    return $users;
}

function get_user($user_mail, $user_pwd) {
    global $bdd;
    //$user_pwd = hash("sha256", $user_pwd);
    $q = $bdd->prepare('SELECT ut_nom, ut_prenom, ut_adressemail, ut_mdp FROM utilisateur WHERE ut_adressemail = :user_mail AND ut_mdp = :user_pwd');
    $q->execute(array(
        'user_mail' => $user_mail,
        'user_pwd' => $user_pwd
    ));

    $userInfo = $q->fetch();

    return $userInfo;
}

function create_user($nom, $prenom, $mail, $pwd) {
    global $bdd;
    $pwd = hash("sha256", $pwd);
    $q = $bdd->prepare("INSERT INTO utilisateur (ut_nom, ut_prenom, ut_adressemail, ut_mdp) VALUES (':nom', ':prenom', ':mail', ':pwd')");
    $q->execute(array(
        'nom' => $nom,
        'prenom' => $prenom,
        'mail' => $mail,
        'pwd' => $pwd
    ));
}

function get_latest_publications() {
    global $bdd;
    $q = $bdd->prepare("SELECT pu_titre, pu_date, pu_contenu CASE WHEN pu_dirfichier IS NOT NULL THEN pu_dirfichier ELSE 0 END AS pu_dirfichier" .
                        "FROM publication ORDER BY pu_date DESC LIMIT 20");
    $q->execute();

    $publications = $q->fetchAll();

    return $publications;
}