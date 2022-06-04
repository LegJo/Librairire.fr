<?php
    if(!function_exists("checkFilePhp")){
        function checkFilePhp(string $fileName)
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

    if(!function_exists("checkFileData")){
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
    
    function OpenFile($path, $mode)
    {
        $fichier=fopen($path,$mode);
        if (!$fichier)
        {
            error_log("erreur impossible d'ouvrir le fichier $path");
            exit;
        } 
        return $fichier;
    }

    function getTabCat() { //Recupere toutes les donnees de categories.csv sous forme de tableau
        $path = FILE_CATEGORIES_CSV;
        $fichier = OpenFile($path, "r");
        $tab = array();
        while (($data = fgetcsv($fichier, 1000, ";")) !== FALSE) {
            $cat = $data[0];
            $code = $data[1];
            $pattern = "/c[0-9]{2}/";
            if ($cat == "") {
                $cat = "sans nom";
            }
            if (!preg_match($pattern, $code)) {
                $code = "cer";
            }           
            $tab[$code] = $cat;
        }
        fclose($fichier);
        return $tab;
    } 

    function getVarUrl(string $var) { //Recupère la variable contenue dans l'URL
        $url = strpos($_SERVER['REQUEST_URI'], $var."=");
        if ($url !== FALSE) {
            $info = substr($_SERVER['REQUEST_URI'], $url+1+strlen($var));

            return $info;
        }
        else {
            $errormsg = "erreur lors de la recuperation de l'url";
            return $errormsg;
            exit;
        }
    }

    function getproductAllInfo(string $code_prod){//identifie un produit à l'aide de son code, puis renvoie toutes informations concernant ce produit
        $fichier = checkFileData(FILE_PRODUCT_XML);
        $livres = simplexml_load_file($fichier);
        $tab=array();
        $founded=0;
        foreach($livres as $produit){
            if($code_prod == $produit->code_prod){
                $founded=1;
                $tab["Titre"]=$produit->Titre;
                $tab["description"]=$produit->description;
                $tab["code_cat"]=$produit->code_cat;
                $tab["auteur"]=$produit->auteur;
                $tab["synopsis"]=$produit->synopsis;
                $tab["langue"]=$produit->langue;
                $tab["ISBN-10"]=$produit->ISBN;
                $tab["editeur"]=$produit->editeur;
                $Extension=strrev(substr(strrev($produit->pic_name),0,strpos(strrev($produit->pic_name),'.')));
                if(preg_match('/png|jpg|jpeg|gif/',$Extension)){
                    $tab["pic_name"]=$produit->pic_name;
                }else{
                    $tab["pic_name"]="erreur";
                }
                if(ctype_digit((string) $produit->quantite) && ($produit->quantite)>=0){
                    $tab["quantite"]=$produit->quantite;
                }else{
                    $tab["quantite"]=0;
                }
                if(!is_nan( (float) $produit->prix) && ($produit->prix)>0){
                    $tab["prix"]=$produit->prix;
                }else{
                    $tab["prix"]=0;
                }           
            }
        }
        if(!$founded) //Si le produit cherché n'est pas trouvé dans le fichier xml, place des valeurs par défaut
        {
            $tab["Titre"]="Not Founded";
            $tab["description"]="Not Founded";
            $tab["code_cat"]="Not Founded";
            $tab["auteur"]="Not Founded";
            $tab["synopsis"]="Not Founded";
            $tab["langue"]="Not Founded";
            $tab["ISBN-10"]="Not Founded";
            $tab["editeur"]="Not Founded";
            $tab["pic_name"]="Not Founded";
            $tab["quantite"]=0;
            $tab["prix"]=1;
            
        }
        return $tab;
    }
    
    function getproductInfo(string $code_prod, $info){// Renvoie une information parmie celle qui existe, si l'information n'existe pas, renvoie un message d'erreur
        if($info=="Titre" || $info=="description" || $info=="pic_name" || $info=="quantite" || $info=="prix" || $info=="auteur"|| $info=="synopsis"|| $info=="langue"|| $info=="ISBN-10"|| $info=="editeur"){
            $tab=getproductAllInfo($code_prod);
            return $tab[$info];
        }
        else{
            echo "<h3> Problème lors de la demande d'information.</h3>";
            exit;
        }
    }

    function getcategorieproduct($code_cat){//A partir d'un code catégorie, renvoie un tableau contenant tous les codes produits des produits qui appartiennent à cette
        if (file_exists(FILE_PRODUCT_XML)){
            $fichier = FILE_PRODUCT_XML;
            $livres = simplexml_load_file($fichier);
            $tab=array();
            $n = 0;
            foreach($livres as $produit){
                if($code_cat == $produit->code_cat){
                    $tab[$n]= (string) $produit->code_prod;
                    $n++;
                }
            }
            return $tab; 
        }else{
            echo "<h3> Aucun produit n'a été trouvé. </h3>";
            exit;
        }
        
    }   

    function getAllProducts() //fonction qui renvoie un tableau avec tous les codes produits de l'ensemble des produits dans les fichiers
    {
        $tabCat=getTabCat();
        $tabProd=[];
        $j=0;
        foreach($tabCat as $code_cat => $name)
        {
            $tmptab = getcategorieproduct($code_cat);
            if(isset($tmptab) && !empty($tmptab))
            {
                for($i=0; $i<count($tmptab) ; $i++)
                {
                    if(!empty($tmptab[$i]))
                    {
                        $tabProd[$j]=$tmptab[$i];
                        $j++;
                    }
                }           
            }                  
        }
        return $tabProd;
    }

    function getUserModel(string $inputConstant)//setup un tableau par default pour secure la fonction getUserAllData
    {
        $model = array("userCode" => $inputConstant,
                "name" => $inputConstant,
                "firstName" => $inputConstant,
                "sex" => $inputConstant,
                "birthDate" => $inputConstant,
                "mail" => $inputConstant,
                "password" => $inputConstant,
                "fullAddress" => array("address" => $inputConstant, "postalCode" => $inputConstant, "city" => $inputConstant),
                "phoneNumber" => $inputConstant,
                "job" => $inputConstant,
                "panier" => $inputConstant,
                "purchaseHistory" =>$inputConstant
        );
        return $model;
    }

    function getUserAllData(string $userCode)//Recupere Toute les infos d'un user dans le .json avec son code user
    {
        $usersJSON = file_get_contents(FILE_USERS_JSON);

        $usersTab = json_decode($usersJSON, true);

        foreach($usersTab as $fields) {
            if($fields["userCode"] === $userCode) {
                foreach($fields as $info){
                    if($info === "")
                    {
                        $info = "Not Founded";
                    }
                }
                return $fields;
            }
        }

        return getUserModel("Not Founded");
    }


    function getUserData(string $userCode, string $info)//Recupere une info demander, d'un user dans le .json avec son code user
    {
        $userField = getUserAllData($userCode);
        if(isset($userField[$info]))
        {
            return $userField[$info];
        }
        else
        {
            return "Not Founded";
        }
    }

    function getUserCodeByMail(string $mail)
    {
        $usersJSON = file_get_contents(FILE_USERS_JSON);

        $usersTab = json_decode($usersJSON, true);

        foreach($usersTab as $fields) {
            if($fields["mail"] === $mail) {
                return $fields["userCode"];
            }
        }
    }

?>