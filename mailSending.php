<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/mailSending.css">
    <title>Envoie de Mail</title>
</head>
<body>
    <?php 
        session_start();
        require_once("./php/display_obj.php");
        include("./header.php");

        $categorie = getTabCat(); //utile pour la suite
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
                ?>
                <form id="form-mailSending" action="./php/mailSendingProcess.php" method="post">
                    <h2>Définition des destinataires</h2>
                    <div class="input_row">
                        <div class="input_label">
                            <label for="recipientType">Type de destinataire :</label>
                            <select name="recipientType" id="">
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                                <option value="theTwo">Les deux</option>
                            </select>
                        </div>
                        <div class="input_label" id="divRecipientChoice">
                            <label for="recipientChoice">Choix des destinataires:</label>
                            <select name="recipientChoice[]" multiple size="5" id="recipientChoice">
                                <option value="all" selected>Tous</option>
                                <optgroup label="Selon l'age">
                                    <option value="minus18">Moins de 18 ans</option>
                                    <option value="18to35">Entre 18 et 35 ans</option>
                                    <option value="35to55">Entre 35 et 55 ans</option>
                                    <option value="plus55">Plus de 55 ans</option>
                                </optgroup>
                                <option value="half">Moitié (1/2)</option>
                                <option value="third">Tiers (1/3)</option>
                            </select>
                        </div>
                    </div>
                    <h2>Définition du message</h2>
                    <div class="input_row">
                        <div class="input_label">
                            <label for="title">Objet / Titre: </label>
                            <input type="text" name="title" id="title" placeholder="Titre">
                        </div>
                    </div>
                    <div class="input_row">
                        <div class="input_label">
                            <label for="messageMail">Message (balise &lt;pdt&gt; pour inclure un produit) : </label>
                            <textarea name="messageMail" id="messageMail" cols="60" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="input_row">
                        <div class="box_info" id="categories">
                            <h4>Catégories :</h4>
                            <select size="8" class="box_select">
                                <option id="" value="" selected disabled>--Sélectionner une catégorie--</option>
                                <?php
                                    //printCatOffice($categorie);
                                    foreach($categorie as $key => $fields) {
                                        echo '<option id="'.$key.'" value="'.$key.'" onclick="Affiche_produits(value)">'.$fields.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="box_info" id="produits">
                            <h4>Produits :</h4>
                            <select size="8" name="selectProduits[]" class="box_select" id="selectProduits" multiple>
                                <option id="" value="aucun" selected>--Sélectionner un produit--</option>
                                <?php
                                    foreach($categorie as $cat=>$key){
                                        $produit = getcategorieproduct($cat);
                                        //printProduitOffice($produit, $cat);
                                        foreach($produit as $code) {
                                            $titre = getproductInfo($code, "Titre");
                                            foreach($titre as $key => $fields) {
                                                echo '<option style="display: none;" class="allProduits '.$cat.' produits" id="'.$code.'" value="'.$code.'">'.$fields.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <p id="display_selected_produits"></p>
                    <div id="divSubmitMail">
                        <input type="submit" class="but_link" name="submitMail" id="submitMail" value="Envoyer le mail">
                    </div>
                </form>
                <div id="display_demo">
                    <h1>Aperçu du Mail</h1>
                </div>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
    <script src="./js/script_mailSending.js"></script>
</body>
</html>