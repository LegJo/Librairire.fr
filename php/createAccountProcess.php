<?php
    session_start();
    require_once("./manage_forms.php");//
    require_once("./constant.php");//

    
    if(isset($_POST["submitRegistration"]))
    {
        $errorNumber = checkingForm("registration", $_POST);
        if($errorNumber === 0)
        {
            $tabDataForm = $_POST;
            $presence = presenceJson(["mail"], $tabDataForm);
            if(is_array($presence) && $presence !== [] && array_sum($presence) === 0)
            {
                unset($_SESSION['errorMessageRegistration']);
                unset($_SESSION['correctInputRegistration']);
                $usersJSON = file_get_contents(ROOT.FILE_USERS_JSON);
                $usersTab = json_decode($usersJSON, true);
    
                $createdUserCode = "u1" . sprintf("%06d", (intval(substr($usersTab[count($usersTab)-1]["userCode"], 2)) + 1));
    
                $passwordHash = password_hash($tabDataForm["password"], PASSWORD_DEFAULT);
    
                $newUserTab = array("userCode" => $createdUserCode,
                                    "name" => $tabDataForm["name"],
                                    "firstName" => $tabDataForm["firstName"],
                                    "sex" => $tabDataForm["sex"],
                                    "birthDate" => $tabDataForm["birthDate"],
                                    "mail" => $tabDataForm["mail"],
                                    "password" => $passwordHash,
                                    "fullAddress" => array("address" => $tabDataForm["address"], "postalCode" => $tabDataForm["postalCode"], "city" => $tabDataForm["city"]),
                                    "phoneNumber" => $tabDataForm["phoneNumber"],
                                    "job" => $tabDataForm["job"],
                                    "panier" => panierTransfer(array()),
                                    "purchaseHistory" => array()
                );
                
                $usersTab[] = $newUserTab;
                file_put_contents(ROOT.FILE_USERS_JSON, json_encode($usersTab, JSON_PRETTY_PRINT));
                $_SESSION["connected"] = $createdUserCode;
                
                header('Location: ../index.php', true, 301);
                exit;
                
            }
            else
            {
                if($presence["mail"] === 1)
                {
                    $_SESSION['errorMessageRegistration']["mail"] = "Ce mail est déjà utilisé. Veuillez en enregistrer un autre";
                }
                header('Location: ../createAccount.php', true, 301);
                exit;
            }
        }
        else
        {
            header('Location: ../createAccount.php', true, 301);
            exit;
        }
    }
    else
    {
        $_SESSION['errorMessageRegistration']['submitRegistration'] = "Veuillez envoyer le formulaire d'inscription.";
        header('Location: ../createAccount.php', true, 301);
        exit;
    }

?>