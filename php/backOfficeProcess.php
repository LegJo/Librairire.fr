<?php
    session_start();

    function rewrite_file_xml($tab, $tabCat){ //Permet de réécrire le fichier de produits en xml pour les backOffices avec un tableau entrez en parametres
        if (!empty($tab)){
            $str='';
            if ($fichier = fopen('../data/products.xml', 'w')){
                $str .= '<?xml version="1.0" encoding="utf-8"?>'."\n";
                $str .= '<produits>'."\n";
                $mes_codes = array_keys($tab);
                $i=0;
                foreach($tab as $code_prod){
                    if(isset($code_prod['Titre']) && isset($code_prod['code_cat']) && isset($code_prod['description']) && isset($code_prod['auteur']) && isset($code_prod['synopsis']) && isset($code_prod['langue']) && isset($code_prod['ISBN-10']) && isset($code_prod['editeur']) && isset($code_prod['quantite'])  && isset($code_prod['prix'])){
                        if($code_prod['Titre']!=NULL && $code_prod['code_cat']!=NULL  && $code_prod['description']!=NULL && $code_prod['auteur']!=NULL && $code_prod['synopsis']!=NULL && $code_prod['langue']!=NULL && $code_prod['ISBN-10']!=NULL && $code_prod['editeur']!=NULL && $code_prod['quantite']!=NULL && $code_prod['prix']!=NULL)
                        {
                            foreach($tabCat as $key =>$code){
                                if($code_prod['code_cat']==$key){
                                    $str .="  ".'<produit>'."\n";
                                    $str .="    ".'<code_prod>'.trim($mes_codes[$i]).'</code_prod>'."\n";
                                    $str .="    ".'<Titre>'.trim($code_prod['Titre']).'</Titre>'."\n";
                                    $str .="    ".'<code_cat>'.trim($code_prod['code_cat']).'</code_cat>'."\n";
                                    $str .="    ".'<description>'.trim($code_prod['description']).'</description>'."\n";
                                    $str .="    ".'<auteur>'.trim($code_prod['auteur']).'</auteur>'."\n";
                                    $str .="    ".'<synopsis>'.trim($code_prod['synopsis']).'</synopsis>'."\n";
                                    $str .="    ".'<langue>'.trim($code_prod['langue']).'</langue>'."\n";
                                    $str .="    ".'<ISBN>'.trim($code_prod['ISBN-10']).'</ISBN>'."\n";
                                    $str .="    ".'<editeur>'.trim($code_prod['editeur']).'</editeur>'."\n";
                                    if(!file_exists("../img/img_products/".$code_prod['pic_name'])){
                                        $code_prod['pic_name']="default.png"; 
                                    }
                                    $Extension=strrev(substr(strrev($code_prod['pic_name']),0,strpos(strrev($code_prod['pic_name']),'.')));
                                    if(preg_match('/png|jpg|jpeg|gif/',$Extension)){
                                        $str .="    ".'<pic_name>'.trim($code_prod['pic_name']).'</pic_name>'."\n";
                                    }else{
                                        $str .="    ".'<pic_name>default.png</pic_name>'."\n";
                                    }
                                    if(ctype_digit((string) $code_prod['quantite']) && ($code_prod['quantite'])>=0){
                                        $str .="    ".'<quantite>'.trim($code_prod['quantite']).'</quantite>'."\n";
                                    }else{
                                        $str .="    ".'<quantite>0</quantite>'."\n";
                                    }
                                    if(!is_nan( (float) $code_prod['prix']) && ($code_prod['prix'])>0){
                                        $str .="    ".'<prix>'.trim($code_prod['prix']).'</prix>'."\n";
                                    }else{
                                        $str .="    ".'<prix>1</prix>'."\n";
                                    }
                                    $str .="  ".'</produit>'."\n";
                                }
                            }
                        }
                    }
                    $i++;
                }
                $str .= '</produits>';
                fputs($fichier, $str);
                fclose($fichier);
            }
            else{
                echo "Erreur aucun produit n'a trouvé";
            }
        }
        else{
            echo "Erreur tableau de produits vide";
        }
    }

    function rewrite_cat($cat)//Ecrase les valeurs du fichier CSV par les codes catégories et leur valeurs associées contenues dans le tableau $cat. format du tableau: $categorie["c01"] = "Name";
    {
        if (!empty($cat))
        {
            $str='';
            if ($fichier = fopen('../data/categories.csv', 'w')) {
                foreach($cat as $key => $fields)
                {
                    $str .= trim($fields).';"'.trim($key).'"'."\n";
                }
                fputs($fichier, $str);
                fclose($fichier);
            }
            else {
                echo "Erreur lors de l'acc&egrave;s au fichier categories.csv";
            }
        }
        else {
            echo "Erreur tableau de cat&eacute;gorie vide en param&egrave;tre de rewrite_cat()";
        }
    }

    if(isset($_POST["obj_final"]) && !empty($_POST["obj_final"]))
    {
        $obj_final = json_decode($_POST["obj_final"]);//decode l'info reçu en transformant le JSONstringifié en objet php 
        // echo "<pre>";
        // var_dump($obj_final);
        // echo "</pre>";
        $tabCat=[];
        $tabProducts=[];        
        foreach($obj_final as $code_cat=>$tabCode_Cat) //on transforme les informations de l'objet pour les mettres dans des tableau indexer qui permettront la réécriture de categories.csv et products.xml
        {
            // echo $code_cat;
            foreach($tabCode_Cat as $code_prod=>$tabCode_prod)
            {
                if($code_prod === "cat_name")
                    $tabCat[$code_cat]= $tabCode_prod;
                else
                {
                    foreach($tabCode_prod as $infos=>$champs)
                    {
                        $tabProducts[$code_prod][$infos]=$champs;
                    }
                }
            }
        }
        // var_dump($tabCat);
        // echo "______________________";
        // echo "<pre>";
        // var_dump($tabProducts);
        // echo "</pre>";
        rewrite_cat($tabCat); //reecriture du ccategories.csv
        rewrite_file_xml($tabProducts, $tabCat); //reecriture du products.xml
        header("Location: ../backOffice.php", true, 301);
        exit;
    }
    else{
        error_log("obj final is not set or is empty");
        header("Location: ../backOffice.php", true, 301);
        exit;
    }

?>
