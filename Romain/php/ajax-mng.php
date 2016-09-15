<?php
require_once ('fonctions-bdd.php');
if(isset($_POST)) {
    switch ($_POST['requete']) {
        case 'search':
            $results = search($_POST['recherche']);
            print json_encode($results);
            break;
        /////////////////////////////////////////////
        ////////////// UTILISATEUR //////////////////
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
        case 'update_description':
            $update = update_description($_POST['ut_id'], $_POST['ut_desc']);
            print json_encode($update);
            break;
        /////////////////////////////////////////////
        ///////////////////// AVIS //////////////////
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
        case 'get_user_like_publi':
            $user_like = get_user_like_publi($_POST['ut_id'], $_POST['pu_id']);
            print json_encode($user_like);
            break;
        /////////////////////////////////////////////
        ////////////// PUBLICATIONS ////////////////
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
        case 'get_one_publication':
            $pub = get_one_publication($_POST['pu_id']);
            print json_encode($pub);
            break;
        case 'get_pending_publications':
            $pending = get_pending_publications();
            print json_encode($pending);
            break;
        case 'validate_publication':
            $valid = validate_publication($_POST['pu_id'], $_POST['modo_id']);
            print json_encode($valid);
            break;
        case 'del_publication':
            $del = del_publication($_POST['pu_id']);
            print json_encode($del);
            break;
        /////////////////////////////////////////////
        ////////////// FAVORIS //////////////////////
        case 'add_favorite':
            $fav = add_favorite($_POST['ut_id'], $_POST['ut_id_aimer']);
            print json_encode($fav);
            break;
        case 'del_favorite':
            $defav = del_favorite($_POST['ut_id'], $_POST['ut_id_aimer']);
            print json_encode($defav);
            break;
        case 'get_favorites':
            $favorites = get_favorites($_POST['ut_id']);
            print json_encode($favorites);
            break;
        /////////////////////////////////////////////
        ////////////// COMMENTAIRES /////////////////
        case 'add_comment':
            $add = add_comment($_POST['co_contenu'], $_POST['ut_id'], $_POST['pu_id']);
            print json_encode($add);
            break;
        case 'del_comment':
            $del = del_comment($_POST['co_id']);
            print json_encode($del);
            break;
        case 'get_comments':
            $comments = get_comments($_POST['co_id']);
            print json_encode($comments);
            break;
        /////////////////////////////////////////////
        default:
            print json_encode("Requete inconnue.");
            break;
    }
}