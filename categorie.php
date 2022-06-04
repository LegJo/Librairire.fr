<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/categorie.css">
    <title>Librairire</title>
</head>
<body>
    <?php 
        session_start();
        require_once("./php/display_obj.php");
        include("./header.php");
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <div id="main_content" class="main_child"> 
                <div id="products_list">
                    <?php
                        $codecat=getVarUrl("cat");
                        $tabcat=getTabCat();
                        if ($codecat == "all")//Affiche tous les produits
                        {
                            foreach ($tabcat as $cat => $name)
                            {
                                ?>
                                    <h2><?=$name;?>s </h2>
                                    <div class="cat_products">
                                <?php
                                setupPage_cat($cat);  
                                echo "</div>";
                            } 
                        }
                        else //Affiche tous les produits d'une seule catégorie
                        { 
                              $exist=0;
                            foreach ($tabcat as $cat => $name){
                                if($codecat==$cat){
                                    ?>
                                        <h2><?=$tabcat[$codecat];?>s </h2>
                                        <div class="cat_products">
                                    <?php
                                    setupPage_cat($codecat);
                                    echo "</div>";
                                    $exist=1;
                                }        
                            }if($exist!=1){
                                ?>
                                    <h3> Cette catégorie n'existe pas. </h3>
                                <?php
                            }  
                        } 
                    ?>
                </div>
                <input class="but_link pointer retour" type="button" value="⇦ Retour à l'acceuil" onclick='location.href="./index.php"'></input>
            </div>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>
