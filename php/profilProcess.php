<?php
    session_start();
    require_once("./manage_forms.php");//
    require_once("./constant.php");//

    
    if(isset($_POST["submitProfile"]) && !empty($_SESSION["connected"]))
    {
        $errorNumber = checkingForm("profile", $_POST);
        if($errorNumber === 0)
        {
            $tabDataForm = $_POST;
            unset($_SESSION['errorMessageProfile']);
            unset($_SESSION['correctInputProfile']);

            $usersJSON = file_get_contents(ROOT.FILE_USERS_JSON);
            $usersTab = json_decode($usersJSON, true);

            foreach($usersTab as $key => $fields)
            {
                if($fields["userCode"] === $_SESSION["connected"]){
                    $usersTab[$key]["name"] = $tabDataForm["name"];
                    $usersTab[$key]["firstName"] = $tabDataForm["firstName"];
                    $usersTab[$key]["sex"] = $tabDataForm["sex"];
                    $usersTab[$key]["birthDate"] = $tabDataForm["birthDate"];
                    $usersTab[$key]["fullAddress"]["address"] = $tabDataForm["address"];
                    $usersTab[$key]["fullAddress"]["postalCode"] = $tabDataForm["postalCode"];
                    $usersTab[$key]["fullAddress"]["city"] = $tabDataForm["city"];
                    $usersTab[$key]["phoneNumber"] = $tabDataForm["phoneNumber"];
                    $usersTab[$key]["job"] = $tabDataForm["job"];
                    break;
                }
            }
            
            file_put_contents(ROOT.FILE_USERS_JSON, json_encode($usersTab, JSON_PRETTY_PRINT));
            
            header('Location: ../profil.php', true, 301);
            exit;
                
        }
        else
        {
            header('Location: ../profil.php', true, 301);
            exit;
        }
    }
    else
    {
        $_SESSION['errorMessageProfile']['submitProfile'] = "Veuillez envoyer le formulaire d'inscription.";
        header('Location: ../profil.php', true, 301);
        exit;
    }

?>