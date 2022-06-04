<?php
    session_start();
    require_once('./constant.php');

    if(!empty($_SESSION['connected']))
    {
        $usersJSON = file_get_contents(ROOT.FILE_USERS_JSON);
        $usersTab = json_decode($usersJSON, true);
        $userCodeOffset = false;
        $changeDone = false;
        foreach($usersTab as $key => $user)
        {
            if($user['userCode'] === $_SESSION['connected'])
            {
                array_splice($usersTab, $key, 1);    // suppression de l'user dans le tableau d'utilisateurs grace à sa clé
                $userCodeOffset = true;
                $changeDone = true;
            }
            if($userCodeOffset && $key < count($usersTab) )
            {
                $modifiedUserCode = substr($usersTab[$key]["userCode"], 0, 2) . sprintf("%06d", (intval(substr($user["userCode"], 2))));
                $usersTab[$key]['userCode'] = $modifiedUserCode;
            }   
        }
        file_put_contents(ROOT.FILE_USERS_JSON, json_encode($usersTab, JSON_PRETTY_PRINT));

        unset($_SESSION['connected']);
        if(isset($_SESSION['panier']))
        {
            unset($_SESSION['panier']);
        }
        header('Location: ../index.php', true, 301);
        exit;
    }
    else
    {
        if(isset($_SESSION['connected']))
        {
            unset($_SESSION['connected']);
        }
        header('Location: ./deconnexion.php', true, 301);
        exit;
    }

?>