<?php
require_once ('fonctions-bdd.php');
if(isset($_POST)) {
    switch ($_POST['requete']) {
        case 'get_user':
            $user = get_user($_POST['user_mail'], $_POST['user_pwd']);
            print json_encode($user);
            break;
        case 'get_users':
            $users = get_users();
            print json_encode($users);
            break;
        default:
            echo 'Nope :C';
            break;
    }
}