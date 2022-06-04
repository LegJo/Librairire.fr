<?php
    session_start();
    require_once("./manage_forms.php");//
    require_once("./constant.php");//

    var_dump($_POST);

    if(isset($_POST["submitContact"]))
    {
        $errorNumber = checkingForm("contact", $_POST);
        if($errorNumber === 0)
        {
            header("Location: ../recapitulatif.php?nom=".$_POST["name"].""."&prenom=".$_POST["firstName"].""."&date_de_contact=".$_POST["contactDate"].""."&email=".$_POST["mail"].""."&message=".$_POST["message"].""."&sexe=".$_POST["sex"].""."&sujet=".$_POST["topic"]);
            exit;
        }
        else
        {
            header('Location: ../contact.php', true, 301);
            exit;
        }
    }
    else
    {
        $_SESSION['errorMessageContact']['submitContact'] = "Veuillez envoyer le formulaire d'inscription.";
        header('Location: ../contact.php', true, 301);
        exit;
    }

?>