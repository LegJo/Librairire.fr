<?php
    session_start();
    require_once("./manage_forms.php");//
    require_once("./constant.php");//

    
    if(isset($_POST["submitValidpan"]))
    {
        $errorNumber = checkingForm("validpan", $_POST); 
        if($errorNumber === 0)
        {
            unset($_SESSION['errorMessageValidpan']);
            unset($_POST['submitValidpan']);
            $_SESSION['formValidpan']=$_POST;
            header('Location: ./commandProcess.php?pro=2', true, 301);
            exit;
        }
        else
        {
            $_SESSION['process_cmd']='CCO1';
            header('Location: ../validPanier.php', true, 301);
            exit;
        }
    }
    else
    {
        $_SESSION['errorMessageValidpan']['submitValidpan'] = "Veuillez envoyer le formulaire d'inscription.";
        $_SESSION['process_cmd']='CCO1';
        header('Location: ../validPanier.php', true, 301);
        exit;
    }

?>