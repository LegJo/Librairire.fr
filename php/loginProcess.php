<?php
    session_start();
    require_once("./manage_forms.php");
    require_once("./constant.php");

    $_SESSION["errorMessageLogin"] = "";

    

    if(isset($_POST["submitLogin"]))
    {
        $errorTrueInput = false;
        $tabDataForm = array();
        foreach($_POST as $key => $dataValue)
        {
            if($key !== "submitLogin")
            {
                $dataValueValid = valid_data($dataValue);

                if($dataValueValid !== $dataValue)
                {
                    $_SESSION['errorMessageLogin'] = "Les données envoyées par le formulaire ne sont pas conforment.";
                    $errorTrueInput = true;
                }
                else
                {
                    $dataValueValid = trim($dataValueValid);
                    $tabDataForm[$key] = $dataValueValid;
                }
            }
        }

        if($errorTrueInput === false)
        {
            $connection_state = false;
            $usersJSON = file_get_contents(ROOT.FILE_USERS_JSON);
            $usersTab = json_decode($usersJSON, true);
            foreach($usersTab as $key => $fields)
            {
                if($fields["mail"] === $tabDataForm["mail"] && password_verify($tabDataForm["password"], $fields["password"])){
                    $_SESSION["connected"] = $fields["userCode"];
                    $connection_state = true;
                    $usersTab[$key]["panier"] = panierTransfer($fields["panier"]);
                    break;
                }
            }
    
            file_put_contents(ROOT.FILE_USERS_JSON, json_encode($usersTab, JSON_PRETTY_PRINT));
    
            if($connection_state === false)
            {
                $_SESSION['errorMessageLogin'] = "L'email ou le mot de passe ne correspondent pas.";
                header("Location: ../login.php", true, 301);
                exit;
            }
            else if($connection_state === true){
                header("Location: ../index.php", true, 301);
                exit;
            }
        }
        else{
            header("Location: ../login.php", true, 301);
            exit;
        }

    }
    else
    {
        $_SESSION['errorMessageLogin'] = "Veuillez envoyer le formulaire de connection.";
        header("Location :../login.php", true, 301);
        exit;
    }
?>