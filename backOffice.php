<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/backOffice.css">
    <script src="./js/script_backOffice.js"></script>
    <title>Back Office</title>
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
            <section id="main_content" class="main_child">
                <?php
                    if(isset($_SESSION['connected']))//vérification si le user est bien un admin
                    {
                        if(!empty($_SESSION['connected']))
                        {
                            $code_user=$_SESSION['connected'];
                            if($code_user[1] === "1") //dans ce cas l'user n'est pas un admin on le redirige vers l'acceuil
                            {
                                header("Location: ./index.php", true, 301);
                                exit;
                            }
                        }
                        else
                        {
                            unset($_SESSION['connected']);
                            header("Location: ./index.php", true, 301);
                            exit;
                        }  
                    }
                    else
                    {
                        header("Location: ./index.php", true, 301);
                        exit;
                    }
                   
                    $categorie = getTabCat(); //utile pour la suite
                    $join_namecat= join(';',$categorie);
                    $join_codecat= join(';',array_keys($categorie));
                    $pefc='';
                    $allProd_infos='';
                    foreach($categorie as $cat => $name){
                        $prodcat=getcategorieproduct($cat);
                        $pefc.= join(";",$prodcat) . '/'; //pefc=produit en fonction de categories
                        for($i=0; $i<count($prodcat); $i++){
                            $prodinfos=getproductAllInfo($prodcat[$i]);
                            $allProd_infos.= join(';',$prodinfos) . '/';
                        }
                        $allProd_infos.='|';
                    }
                ?>
                <script>
                    initCat("<?=$join_codecat;?>","<?=$join_namecat;?>", "<?=$pefc;?>", "<?=$allProd_infos;?>");
                    test();
                </script>
                <h2>Gestion des fichiers :</h2>
                <div id="align_block">
                    <div class="box_info" id="categories">
                        <h4>Catégories :</h4>
                        <!-- Select des tous les catégories -->
                        <select size="8" class="box_select pointer">
                            <option id="" value="" selected disabled>--Sélectionner une catégorie--</option>
                            <?php
                                printCatOffice($categorie);
                            ?>
                        </select>
                        <!--  -->
                        <!-- Boutton Ajouter/Supprimer une catégorie -->
                        <input type="button" id="but_rem_cat" class="but_link pointer box_delete" value="Supprimer la catégorie" title="" onclick="remCat(title)">
                        <input type="button" id="but_add_cat" class="but_link pointer box_delete" value="Ajouter une catégorie" title="" onclick="Form_add_cat(title)">
                        <!--  -->
                    </div>
                    <div class="box_info" id="produits">
                        <h4>Produits :</h4>
                        <!-- Select des tous les produits -->
                        <select size="8" class="box_select pointer">
                            <option id="" value="" selected disabled>--Sélectionner un produit--</option>
                            <?php
                                foreach($categorie as $cat=>$key){
                                    $produit = getcategorieproduct($cat);
                                    printProduitOffice($produit, $cat);
                                }
                            ?>
                        </select>
                        <!--  -->
                        <!-- Boutton Ajouter/Supprimer une produit -->
                        <input type="button" id="but_rem_prod" class="but_link pointer box_delete" value="Supprimer le produit" title="" onclick="remProd(title)">
                        <input type="button" id="but_add_prod" class="but_link pointer box_delete" value="Ajouter un produit" title="" onclick="Form_add_prod(title)">
                        <!--  -->
                    </div>
                    <div class="box_info" id="details">
                        <h4>Détails :</h4>
                        <!-- Select des tous les infos des produits -->
                        <select size="12" class="box_select pointer">
                            <option id="" value="" selected disabled>--Sélectionner un détail--</option>
                            <?php
                            foreach($categorie as $cat=>$key){
                                $produit = getcategorieproduct($cat);
                                foreach($produit as $prod){
                                    printDetailOffice($prod);
                                }
                            }
                            ?>
                        </select>
                        <!--  -->
                    </div>
                </div>
                <!-- Boutton d'envoi de l'objet JS -->
                <input type="button" id="but_sendObj" class="pointer but_link" value="Envoyer au serveur ⇨" onclick="SendObjToServ()">
                <!--  -->
                <!-- "Formulaire" de création d'une nouvelle catégorie -->
                <form name="Myform" id="form_cat" method="post" class="box_info" action="" style="display: none;" onsubmit="">
                    <h3>Création d'une nouvelle categorie</h4>
                    <input id="name_cat_input1" name="name_cat_input1" style="height: 50px; width: 180px" placeholder="Nom de la nouvelle Catégorie" type="text" value="">
                    <h4>Création du premier produit de la Categorie</h4>
                    <div id="div_form_cat">
                        <input id="titre_input1" name="titre_input1" style="height: 50px; width: 180px" placeholder="Titre du produit à créer" type="text" value="">
                        <input id="description_input1" name="description_input1" style="height: 50px; width: 180px" placeholder="Description du produit à créer" type="text" value="">
                        <input id="auteur_input1" name="auteur_input1" style="height: 50px; width: 180px" placeholder="Auteur du produit à créer" type="text" value="">
                        <input id="synopsis_input1" name="synopsis_input1" style="height: 50px; width: 180px" placeholder="Synopsis du produit à créer" type="text" value="">
                        <input id="langue_input1" name="langue_input1" style="height: 50px; width: 180px" placeholder="Langue du produit à créer" type="text" value="">
                        <input id="ISBN_input1" name="ISBN_input1" style="height: 50px; width: 180px" placeholder="ISBN du produit à créer" type="text" value="">
                        <input id="editeur_input1" name="editeur_input1" style="height: 50px; width: 180px" placeholder="Editeur du produit à créer" type="text" value="">
                        <input id="pic_name_input1" name="pic_name_input1" style="height: 50px; width: 180px" placeholder="Pic_name du produit à créer" type="text" value="">
                        <input id="quantite_input1" name="quantite_input1" style="height: 50px; width: 180px" placeholder="Quantite du produit à créer" type="text" value="">
                        <input id="prix_input1" name="prix_input1" style="height: 50px; width: 180px" placeholder="Prix du produit à créer" type="text" value="">
                    </div>
                    <input id="envoi1" type="button" class="but_link pointer" value="Valider" onclick="addCategorie()">
                    <input id="cancel1" type="button" class="but_link pointer" value="Annuler" onclick="display_none_form_cat()">
                </form>
                <!-- -->
                <!-- "Formulaire" de création d'un nouveau produit -->
                <form name="Myform" id="form_prod" method="post" class="box_info" action="" style="display: none;" onsubmit="">
                    <h3>Details du produit à ajouter</h4>
                    <div id="div_form_prod">
                        <input id="titre_input2" name="titre_input2" style="height: 50px; width: 180px" placeholder="Titre du produit à créer" type="text" value="">
                        <input id="description_input2" name="description_input2" style="height: 50px; width: 180px" placeholder="Description du produit à créer" type="text" value="">
                        <input id="auteur_input2" name="auteur_input2" style="height: 50px; width: 180px" placeholder="Auteur du produit à créer" type="text" value="">
                        <input id="synopsis_input2" name="synopsis_input2" style="height: 50px; width: 180px" placeholder="Synopsis du produit à créer" type="text" value="">
                        <input id="langue_input2" name="langue_input2" style="height: 50px; width: 180px" placeholder="Langue du produit à créer" type="text" value="">
                        <input id="ISBN_input2" name="ISBN_input2" style="height: 50px; width: 180px" placeholder="ISBN du produit à créer" type="text" value="">
                        <input id="editeur_input2" name="editeur_input2" style="height: 50px; width: 180px" placeholder="Editeur du produit à créer" type="text" value="">
                        <input id="pic_name_input2" name="pic_name_input2" style="height: 50px; width: 180px" placeholder="Pic_name du produit à créer" type="text" value="">
                        <input id="quantite_input2" name="quantite_input2" style="height: 50px; width: 180px" placeholder="Quantite du produit à créer" type="text" value="">
                        <input id="prix_input2" name="prix_input2" style="height: 50px; width: 180px" placeholder="Prix du produit à créer" type="text" value="">
                        <input type="hidden" id="code_categorie" value="">
                    </div>
                    <input id="envoi2" type="button" class="but_link pointer" value="Valider" onclick="addProduit()">
                    <input id="cancel2" type="button" class="but_link pointer" value="Annuler" onclick="display_none_form_prod()">
                </form>
                <!--  -->
                <!-- "Formulaire" de modification d'une info d'un produit -->
                <form name="Myform" id="form_details" method="post" class="box_info" action="" style="display: none;" onsubmit="">
                    <div id="div_form_details">
                        <textarea id="modificateur" name="modificateur" placeholder="Ecrivez l'information" type="text"></textarea>
                        <input type="hidden" id="rubrique_infos" value="">
                        <input type="hidden" id="code_produit" value="">
                        <input type="hidden" id="code_cati" value="">
                    </div>
                    <input id="envoi3" type="button" class="but_link pointer" value="Valider" onclick="modifierInfos()">
                    <input id="cancel3" type="button" class="but_link pointer" value="Annuler" onclick="display_none_form_details()">
                </form>
                <!---->
                <!-- On écrit l'objet JS dans ce input hidden pour l'écrire dans le fichier "product.xml par la suite -->
                <form name="BO_changes" id="BO_changes" method="post" action="./php/backOfficeProcess.php">
                    <input type="hidden" name="obj_final" id="obj_final" value="">
                </form>
                <!---->
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
    <script src="./js/script_backOffice.js"></script>
</body>
</html>