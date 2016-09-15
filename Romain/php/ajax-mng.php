<?php
require_once ('fonctions-bdd.php');
if(isset($_POST)) {
    switch ($_POST['requete']) {
        case 'get_users':
            $users = get_users();
            print json_encode($users);
            break;
        case 'get_user':
            $user = get_user($_POST['ut_mail'], $_POST['ut_pwd']);
            print json_encode($user);
            break;
        case 'create_user_classic':
            $new_user = create_user_classic($_POST['ut_prenomnom'], $_POST['ut_mail'], $_POST['ut_pwd']);
            print json_encode($new_user);
            break;
        case 'create_user_fb':
            $new_user_fb = create_user_fb($_POST['ut_prenomnom'], $_POST['ut_id_fb']);
            print json_encode($new_user_fb);
            break;
        case 'create_user_tw':
            $new_user_tw = create_user_tw($_POST['ut_prenomnom'], $_POST['ut_id_tw']);
            print json_encode($new_user_tw);
            break;
        case 'like':
            $result = like($_POST['ut_id'], $_POST['pu_id']);
            print json_encode($result);
            break;
        case 'dislike':
            $result = dislike($_POST['ut_id'], $_POST['pu_id']);
            print json_encode($result);
            break;
        case 'get_likes_dislikes':
            $likes_dislikes = get_likes_dislikes($_POST['pu_id']);
            print json_encode($likes_dislikes);
            break;
        case 'get_latest_publications':
            $latest_pubs = get_latest_publications();
            print json_encode($latest_pubs);
            break;
        case 'get_publications':
            $pubs = get_publications();
            print json_encode($pubs);
            break;
        case 'get_user_publications':
            $user_pubs = get_user_publications($_POST['ut_id']);
            print json_encode($user_pubs);
            break;
        case 'create_publication':
            $creation = create_publication($_POST['pu_titre'], $_POST['pu_contenu'], $_POST['pu_dirfichier'], $_POST['pu_id_aut'], $_POST['pu_type_fichier']);
            print json_encode($creation);
            break;
        default:
            echo 'Requete inconnue';
            break;
    }
}