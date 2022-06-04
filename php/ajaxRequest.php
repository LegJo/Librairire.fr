<?php
    //  require_once("./manage_files.php");
    require_once("./manage_panier.php");

    function change_prod_stock(string $code_prod, $newquant){ // Permet d'actualiser la quantité restante d'un produit
        $fichier = '../data/products.xml';
        $livres = simplexml_load_file($fichier);
        foreach($livres as $produit){
            if($code_prod == $produit->code_prod){
                $produit->quantite=$newquant;
            }
        }
        $livres->saveXML($fichier);
    }

    function getquantity(string $code_prod){ //a pour utilité principal de retourner la quantité d'un produit entré en param, mais renvoie aussi d'autres infos pour l'actualisation dans le DOM
        $fichier = '../data/products.xml';
        $livres = simplexml_load_file($fichier);
        foreach($livres as $produit){
            if($code_prod == $produit->code_prod){
                 $tab["quantite"]=$produit->quantite;
                 $tab["Titre"]=$produit->Titre;
                 $tab["pic_name"]=$produit->pic_name;
                 $tab["prix"]=$produit->prix;
            }
        }
        if(!isset($tab) || empty($tab)){
            return 0;
        }
        return $tab;
    }

    function valid_quantity(string $code_prod, string $demande){ // Permet de verifier si la commande est commandable en fonction des stocks restants
        $nam_quant= getquantity($code_prod);
        if($nam_quant==0){
            return 0;
        }
        $quantite=$nam_quant["quantite"];
        $demande=intval($demande);
        $quantite=intval($quantite);
        $result= $quantite + $demande;
        if($result<0){
            $quantite=0;
            if($demande<0){
                $demande=-$demande;
            }
            $demande=$demande+$result; 
        }else{
            $quantite=$result;
            if($demande<0){
                $demande=-$demande;
            }
        }
        change_prod_stock($code_prod,$quantite); //modification du stock en consequence
        $tab[0]=$quantite;
        $tab[1]=$demande;
        $tab[2]=$nam_quant["Titre"];
        $tab[3]=$nam_quant["prix"];
        $tab[4]=$nam_quant["pic_name"];
        return $tab;
    }

    function AjaxAddToCart($codepdt, $qtcmd) //fonction lorsque que la requete ajax provient d'un ajout dans le panier
    {   
        $tmptab=valid_quantity($codepdt, $qtcmd);
        if($tmptab==0){
            echo "0";
            return 0;
        }  
        $ret_ses=addToPanier($codepdt,$tmptab[1]);                                                  
        if( isset($_SESSION['connected']) && !empty($_SESSION['connected'])) 
        {
            panierTransferFromSession();
        }                                                                                             //tmptab[0]=quantite restante dans les fichiers tmptab[1]=quantité ayant reussis a etre commander ; ret_ses est la nouvel quantité presente dns le $_SESSION['panier] du produit correspondant
        echo $tmptab[0].";".$tmptab[1].";".$ret_ses.";".$tmptab[2].";".$tmptab[3].";".$tmptab[4];    //tmptab[2]=Titre du produit, tmptab[3]=prix du produit, tmptab[4]=nom img produit,
        return 0;
    }

    function AjaxChgQtInCart($codepdt, $qt, string $sign) //fonction lorsque que la requete ajax provient d'une modification de quantité dans le panier
    {
        if($sign == 'plus')
        {
            $tmptab=valid_quantity($codepdt, '-1'); 
            $qt = intval($qt)+1;
            $sign = '-';
        }
        elseif($sign == 'minus')
        {
            if(intval($qt)>1)
            {
                $tmptab=valid_quantity($codepdt, '1');
                $qt = intval($qt)-1;
                $sign = '+';
            }
            else
            {
                $tmptab[0]="no change";
                $tmptab[1]="0";
                $ret_ses="no change";
                $sign = '';
            }
        }
        else
        {
            echo "erreur, ur not allowed to do that => $sign";
            exit;
        }

        if(is_numeric($qt) && $tmptab[1] != 0)
            $ret_ses = chProdQuantPan($codepdt, $qt);
        else
            $ret_ses = "no change";

        if( isset($_SESSION['connected']) && !empty($_SESSION['connected'])) 
        {
            panierTransferFromSession();
        }
        echo $tmptab[0].";".$sign.$tmptab[1].";".$ret_ses.";".$tmptab[2].";".$tmptab[3].";".$tmptab[4]; //tmptab[0]=quantite restante dans les fichiers ; tmptab[1]=quantité ayant ete ajouté/retiré ; ret_ses est la nouvel quantité presente dns le $_SESSION['panier] du produit correspondant 
        return 0;                                                                                        //tmptab[2]=Titre du produit, tmptab[3]=prix du produit, tmptab[4]=nom img produit,
    }

    function AjaxRemProd($codepdt, $qt) //fonction lorsque que la requete ajax provient d'une suppression de produit dans le panier
    {   
        if(intval($qt)>=0)
        {
            $tmptab=valid_quantity($codepdt, $qt);
            $ret_ses=RemoveFromPanier($codepdt);
        }
        if( isset($_SESSION['connected']) && !empty($_SESSION['connected'])) 
        {
            panierTransferFromSession();
        }
        echo $tmptab[0].";".$tmptab[1].";".$ret_ses.";".$tmptab[2].";".$tmptab[3].";".$tmptab[4];  //tmptab[0]=quantite restante dans les fichiers tmptab[1]=quantité ayant ete rajouter dans les fichier ; ret_ses est la nouvel quantité presente dns le $_SESSION['panier] du produit correspondant 
        return 0;                                                                                        //tmptab[2]=Titre du produit, tmptab[3]=prix du produit, tmptab[4]=nom img produit,
    }
        
    if($_POST['fct']!== null)
    {
        $fct=$_POST['fct'];
        if($fct=='addpdt')
        {
            if($_POST['codepdt']!== null && $_POST['qtcmd']!== null)
            {
                AjaxAddToCart($_POST['codepdt'], $_POST['qtcmd']);
            }
            else
                echo "error, not enough POST in ajax request";
        }
        elseif($fct=='chgqt')
        {
            if($_POST['codepdt']!== null && $_POST['qt']!== null && $_POST['sign'])
            {
                AjaxChgQtInCart($_POST['codepdt'], $_POST['qt'], $_POST['sign']);
            }
            else
                echo "error, not enough POST in ajax request";
        }
        elseif($fct=='remprod')
        {
            if($_POST['codepdt']!== null && $_POST['qt']!== null)
            {
                AjaxRemProd($_POST['codepdt'], $_POST['qt']);
            }
            else
                echo "error, not enough POST in ajax request";
        }
        else
        {
            echo "error POST fct invalid in ajax request";
        }
    }
    else
    {
        echo "error POST fct not defined in ajax request";
    }
?>