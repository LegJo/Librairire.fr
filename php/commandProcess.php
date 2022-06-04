<?php
    session_start();
    require_once("./manage_panier.php");

    function getVarUrl(string $var) { //recupere une variable (passer en parametre) dans  l'url à la maniere d'une mehode Get 
        $url = strpos($_SERVER['REQUEST_URI'], $var."=");
        if ($url !== FALSE) {
            $info = substr($_SERVER['REQUEST_URI'], $url+1+strlen($var));

            return $info;
        }
        else {
            error_log("erreur lors de la recuperation de l'url");
            echo "erreur lors de la recuperation de l'url";
            exit;
        }
    }

    /*ce fichier sert à verifier que toute les étapes du processus de commande son faite dans l'ordre*/
    
    $pro = getVarUrl('pro');
    if($pro == 1)
    {
        if(isset($_SESSION['connected']))
        {   
            $_SESSION['process_cmd'] = 'CCO1';// pour s'assurer que le processus de commande est bien passé par cette page et est donc valide. CCO = check connected order
            header('Location: ../validPanier.php', true, 301);
            exit;
        }
        else {
            $_SESSION['popcmdcon']=1;
            header('Location: ../panier.php', true, 301);
            exit;
        }
    }
    elseif($pro == 2)
    {
        if(isset($_SESSION['connected']))
        {   
            $_SESSION['process_cmd'] = 'CCO2';// pour s'assurer que le processus de commande est bien passé par cette page et est donc valide. CCO = check connected order
            header('Location: ../facture.php', true, 301);
            exit;
        }
        else {
            $_SESSION['popcmdcon']=1;
            header('Location: ../panier.php', true, 301);
            exit;
        }
    }
    else 
    {
        error_log('invalid "pro" in commandProcess');
        header('Location: ../index.php', true, 301);
        exit;
    }


?>