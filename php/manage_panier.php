<?php
    session_start();

    //test de l'existence de la fonction pour éviter les conflit en raison des inclusions
    if(!function_exists("checkFilePhp")){
        function checkFilePhp(string $fileName) //fonction permettant d'atteindre le fichier php voulu selon le point de départ
        {
            if(file_exists("./".$fileName)){
                return "./".$fileName;
            }
            else if(file_exists("./php/".$fileName)){
                return "./php/".$fileName;
            }
            else{
                exit('Require failed! Error: File not founded');
            }
        }
    }
    require_once(checkFilePhp("constant.php"));
    
    if(!function_exists("checkFileData")){ //fonction permettant d'atteindre le fichier de donné voulu selon le point de départ
        function checkFileData(string $fileName){
            if(file_exists($fileName)){
                return $fileName;
            }
            else if(file_exists(ROOT.$fileName)){
                return ROOT.$fileName;
            }
            else{
                exit('Require failed! Error: File not founded');
            }
        }
    }


	
	function addToPanier(string $codepdt, int $quantity) //fonction qui ajoute la quantité d'un produit indiquer dans le panier (dans le $_SESSION['panier])
    {   
        if($quantity==0){
            return 0;
        }
        if (!isset($_SESSION['panier']))
        {
            $_SESSION['panier'][$codepdt] = $quantity;
        }
        else
        {
            $founded = 0;
            foreach($_SESSION['panier'] as $key => $champ)
            {
                if($key == $codepdt)
                {
                    $_SESSION['panier'][$codepdt] = intval($champ) + intval($quantity);
                    $founded=1;
                }
            }
            if(!$founded)
            {
                $_SESSION['panier'][$codepdt]=$quantity;
            }
        }
        return $_SESSION['panier'][$codepdt];
    }

    function RemoveFromPanier(string $codepdt) //fonction supprime un produit indiquer du panier (dans le $_SESSION['panier])
    {
        if (!isset($_SESSION['panier']))
        {
            $_SESSION['errormsg']="empty panier";
            return "product to sup not founded"; 
        }
        else
        {            
            if( array_key_exists($codepdt , $_SESSION['panier']) )
            {
                unset($_SESSION['panier'][$codepdt]);
            }
            else
            {
                $_SESSION['errormsg']="product not founded";
                return $_SESSION['errormsg']; 
            }
        }
        if(count($_SESSION['panier'])==0)
        {
            unset($_SESSION['panier']); //on unset le panier s'il est vide 
        }
        return 0; //return 0 si ça s'est bien supprimer
    }

    function chProdQuantPan(string $codepdt, int $quantity) //fonction qui chnge la quantité d'un produit indiquer du panier (dans le $_SESSION['panier])
    {
        if($quantity <= 0)
        {
            RemoveFromPanier($codepdt);
        }
        else
        {
            if (!isset($_SESSION['panier']))
            {
                $_SESSION['panier'][$codepdt] = $quantity;
            }
            else
            {
                $founded = 0;
                foreach($_SESSION['panier'] as $key => $champ)
                {
                    if($key == $codepdt)
                    {
                        $_SESSION['panier'][$codepdt] = intval($quantity);
                        $founded=1;
                    }
                }
                if(!$founded)
                {
                    $_SESSION['panier'][$codepdt]=$quantity;
                }
            }
        }
        return $_SESSION['panier'][$codepdt];
        
    }

    //fonction qui transfer le contenu de la session panier dans le champ panier de l'utilisateur (utilisé lors de la déconnexion)
    function panierTransferFromSession()
    {
        if(!empty($_SESSION['connected']))
        {
            // echo "<script>alert('test');</script>";
            $transferCompleted = false;
            $usersJSON = file_get_contents(checkFileData(FILE_USERS_JSON));
            $usersTab = json_decode($usersJSON, true);
    
            foreach($usersTab as $key => $user)
            {
                if($user['userCode'] === $_SESSION['connected'])
                {
                    if(isset($_SESSION['panier']) && isset($user['panier']))
                    {
                        $usersTab[$key]['panier'] = $_SESSION['panier'];
                    }
                    else if(isset($user['panier']))
                    {
                        $usersTab[$key]['panier'] = [];
                    }
                    $transferCompleted = true;
                    break;
                }
            }
    
            if($transferCompleted === false)
            {
                unset($_SESSION['connected']);
                header('Location: ./deconnexion.php', true, 301);
                exit;
            }
            else{
                file_put_contents(checkFileData(FILE_USERS_JSON), json_encode($usersTab, JSON_PRETTY_PRINT));
            }
        }
    }

    /*fonction qui transfer le contenu entre le panier de l'utlisateur et de la session ou inversement
      lors de l'inscription et de la connexion selon la situation */
    function panierTransfer(array $panier){
        if(!isset($_SESSION["panier"]) && $panier === [])
        {
            return $panier;
        }
        else if($panier === [])
        {
            return $_SESSION["panier"];
        }
        else
        {
            $_SESSION["panier"] = $panier;
            return $panier;
        }
    }

    //fonction qui transefer le panier à l'historique de l'utlisateur après une commande
    function panierTransferToHistory()
    {
        if(!empty($_SESSION['connected']))
        {
            // echo "<script>alert('test');</script>";
            $transferCompleted = false;
            $usersJSON = file_get_contents(checkFileData(FILE_USERS_JSON));
            $usersTab = json_decode($usersJSON, true);
    
            foreach($usersTab as $key => $user)
            {
                if($user['userCode'] === $_SESSION['connected'])
                {
                    if(isset($_SESSION['panier']) && isset($user['purchaseHistory']))
                    {
                        $numCommande = "Commande".sprintf("%d", (intval(count($usersTab[$key]["purchaseHistory"])) + 1));
                        date_default_timezone_set('Europe/Paris');
                        $usersTab[$key]['purchaseHistory'][$numCommande]['purchaseDate'] = date("Y-m-d H:i:s");
                        $usersTab[$key]['purchaseHistory'][$numCommande]['panier'] = $_SESSION['panier'];

                    }
                    $transferCompleted = true;
                    break;
                }
            }
    
            if($transferCompleted === false)
            {
                unset($_SESSION['connected']);
                header('Location: ./deconnexion.php', true, 301);
                exit;
            }
            else{
                file_put_contents(checkFileData(FILE_USERS_JSON), json_encode($usersTab, JSON_PRETTY_PRINT));
            }
        }
    }

    //fonction qui supprime l'historique de l'utlisateur
    function removeHistoryCmd()
    {
        if(isset($_SESSION['connected']) )
        {
           
            $usersJSON = file_get_contents(checkFileData(FILE_USERS_JSON));
            $usersTab = json_decode($usersJSON, true);
            foreach($usersTab as $key => $user)
            {
                if($user['userCode'] === $_SESSION['connected'])
                {
                    if(isset($user['purchaseHistory']))
                    {    
                        $usersTab[$key]['purchaseHistory']=[];
                    }
                    break;
                }
            }
            file_put_contents(checkFileData(FILE_USERS_JSON), json_encode($usersTab, JSON_PRETTY_PRINT));
        }
        else{
            unset($_SESSION['connected']);
            header('Location: ./login.php', true, 301);
            exit;
        }
    }

?>
