<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/profil.css">
    <title>Librairire</title>
</head>
<body>
    <?php 
        require_once("./php/manage_files.php");
        require_once("./php/manage_panier.php");

        // Suppression de l'historique
        $remHistory=getVarUrl("supHist");
        if($remHistory != "erreur lors de la recuperation de l'url" && intval($remHistory))
        {
            removeHistoryCmd();
        }
        if(!empty($_SESSION['connected']))
        {
            $userTab = getUserAllData($_SESSION['connected']);
        }
        else
        {
            if(isset($_SESSION['connected']))
            {
                unset($_SESSION['connected']);
            }
            header('Location: ./login.php', true, 301);
            exit;
        }

        
        if(!empty($_SESSION['errorMessageProfile']))
        {
            $tabErrorMessage = [];
            foreach($_SESSION['errorMessageProfile'] as $errorKey => $error) {
                $tabErrorMessage[$errorKey] = $error;
            }
            unset($_SESSION['errorMessageProfile']);
        }

        if(!empty($_SESSION["correctInputProfile"]))
        {
            $tabCorrectInput = [];
            foreach($_SESSION['correctInputProfile'] as $inputName => $input) {
                $tabCorrectInput[$inputName] = $input;
            }
            unset($_SESSION["correctInputProfile"]);
        }

        require_once("./php/display_obj.php");

        include("./header.php");
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" class="main_child">
                
                <form  id="form-profile" onsubmit="return (checkingForm('profile'))" action="./php/profilProcess.php" method="post">
                    <div id="formprofil">    
                        <h1>Profil</h1>
                        <table>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="name">Nom</label>
                                        <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="name" class="input-profile <?=$classValid?>" required id="name" placeholder="Nom" size="32" disabled="disabled" value="<?php if(!empty($tabCorrectInput["name"])){echo $tabCorrectInput["name"];}else if(!empty($userTab["name"])){echo $userTab["name"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-name" class="errorMessage"><?php if(isset($tabErrorMessage["name"])){echo $tabErrorMessage["name"];}else{echo "";} ?></p>
                                    </div> 
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="firstName">Prénom</label>
                                        <?php $classValid = (!empty($tabErrorMessage["firstName"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="firstName" class="input-profile <?=$classValid?>" required id="firstName" placeholder="Prénom" size="32" disabled="disabled" value="<?php if(isset($tabCorrectInput["firstName"])){echo $tabCorrectInput["firstName"];}else if(!empty($userTab["firstName"])){echo $userTab["firstName"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-firstName" class="errorMessage"><?php if(isset($tabErrorMessage["firstName"])){echo $tabErrorMessage["firstName"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1">
                                    <div class="input_label">
                                        <label for="sexChoice">Sexe</label> 
                                        <?php $classValid = (!empty($tabErrorMessage["sex"])) ? 'invalidInput' : '' ?>
                                        <div id="sexChoice">
                                            <input type="radio" class="input-profile <?=$classValid?>" id="sexMan" name="sex" value="Man" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Man"){echo "checked";}else if(isset($userTab["sex"]) && $userTab["sex"] === "Man"){echo "checked";}else{echo "disabled=\"disabled\"";} ?>>
                                            <label for="sexMan">Homme</label>
                                        
                                            <input type="radio" class="input-profile <?=$classValid?>" id="sexWoman" name="sex" value="Woman" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Woman"){echo "checked";}else if(isset($userTab["sex"]) && $userTab["sex"] === "Woman"){echo "checked";}else{echo "disabled=\"disabled\"";} ?>>
                                            <label for="sexWoman">Femme</label>

                                            <input type="radio" class="input-profile <?=$classValid?>" id="sexOther" name="sex" value="Other" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Other"){echo "checked";}else if(isset($userTab["sex"]) && $userTab["sex"] === "Other"){echo "checked";}else{echo "disabled=\"disabled\"";} ?>>
                                            <label for="sexOther">Autre</label>
                                        </div>  
                                        <p id="errorMessage-profile-sex" class="errorMessage"><?php if(isset($tabErrorMessage["sex"])){echo $tabErrorMessage["sex"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="birthDate">Date de naissance</label>
                                        <?php $classValid = (!empty($tabErrorMessage["birthDate"])) ? 'invalidInput' : '' ?>
                                        <input type="date" name="birthDate" class="input-profile <?=$classValid?>" required id="birthDate" disabled="disabled" value="<?php if(!empty($tabCorrectInput["birthDate"])){echo $tabCorrectInput["birthDate"];}else if(!empty($userTab["birthDate"])){echo $userTab["birthDate"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-birthDate" class="errorMessage"><?php if(isset($tabErrorMessage["birthDate"])){echo $tabErrorMessage["birthDate"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="input_label">
                                        <label for="address">Adresse</label>
                                        <?php $classValid = (!empty($tabErrorMessage["address"])) ? 'invalidInput' : '' ?>
                                        <textarea class="adresse input-profile <?=$classValid?>" name="address" required id="address" rows="3" disabled="disabled" ><?php if(isset($tabCorrectInput["address"])){echo $tabCorrectInput["address"];}else if(!empty($userTab["fullAddress"]["address"])){echo $userTab["fullAddress"]["address"];}else{echo "";} ?></textarea>
                                        <p id="errorMessage-profile-address" class="errorMessage"><?php if(isset($tabErrorMessage["address"])){echo $tabErrorMessage["address"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="postalCode">Code postal</label>
                                        <?php $classValid = (!empty($tabErrorMessage["postalCode"])) ? 'invalidInput' : '' ?>
                                        <input type="number" name="postalCode" class="input-profile <?=$classValid?>" required id="postalCode" placeholder="Code postal" min="00000" max="99999" class="bouton" disabled="disabled" value="<?php if(isset($tabCorrectInput["postalCode"])){echo $tabCorrectInput["postalCode"];}else if(!empty($userTab["fullAddress"]["postalCode"])){echo $userTab["fullAddress"]["postalCode"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-postalCode" class="errorMessage"><?php if(isset($tabErrorMessage["postalCode"])){echo $tabErrorMessage["postalCode"];}else{echo "";} ?></p> 
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="city">Ville</label>
                                        <?php $classValid = (!empty($tabErrorMessage["city"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="city" class="input-profile <?=$classValid?>" id="city" required placeholder="Ville" disabled="disabled" value="<?php if(isset($tabCorrectInput["city"])){echo $tabCorrectInput["city"];}else if(!empty($userTab["fullAddress"]["city"])){echo $userTab["fullAddress"]["city"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-city" class="errorMessage"><?php if(isset($tabErrorMessage["city"])){echo $tabErrorMessage["city"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="phoneNumber">Numéro de Téléphone</label>
                                        <?php $classValid = (!empty($tabErrorMessage["phoneNumber"])) ? 'invalidInput' : '' ?>
                                        <input type="tel" name="phoneNumber" class="input-profile <?=$classValid?>"  required id="phoneNumber" placeholder="Téléphone" disabled="disabled" value="<?php if(isset($tabCorrectInput["phoneNumber"])){echo $tabCorrectInput["phoneNumber"];}else if(!empty($userTab["phoneNumber"])){echo $userTab["phoneNumber"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-phoneNumber" class="errorMessage"><?php if(isset($tabErrorMessage["phoneNumber"])){echo $tabErrorMessage["phoneNumber"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="job">Métier</label>
                                        <?php $classValid = (!empty($tabErrorMessage["job"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="job" class="input-profile <?=$classValid?>" id="job" required placeholder="Métier" disabled="disabled" value="<?php if(isset($tabCorrectInput["job"])){echo $tabCorrectInput["job"];}else if(!empty($userTab["job"])){echo $userTab["job"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-job" class="errorMessage"><?php if(isset($tabErrorMessage["job"])){echo $tabErrorMessage["job"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="mail">Adresse mail</label>
                                        <?php $classValid = (!empty($tabErrorMessage["mail"])) ? 'invalidInput' : '' ?>
                                        <input type="email" name="mail" id="mail" class="input-profile <?=$classValid?>" required placeholder="Adresse mail" size="32" disabled="disabled" value="<?php if(isset($tabCorrectInput["mail"])){echo $tabCorrectInput["mail"];}else if(!empty($userTab["mail"])){echo $userTab["mail"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-mail" class="errorMessage"><?php if(isset($tabErrorMessage["mail"])){echo $tabErrorMessage["mail"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label" id="divConfirmationMail">
                                        <label for="confirmationMail">Confirmation mail</label>
                                        <?php $classValid = (!empty($tabErrorMessage["confirmationMail"])) ? 'invalidInput' : '' ?>
                                        <input type="email" name="confirmationMail" id="confirmationMail" class="input-profile <?=$classValid?>" required placeholder="Adresse mail" size="25" disabled="disabled" value="<?php if(isset($tabCorrectInput["confirmationMail"])){echo $tabCorrectInput["confirmationMail"];}else if(!empty($userTab["mail"])){echo $userTab["mail"];}else{echo "";} ?>">
                                        <p id="errorMessage-profile-confirmationMail" class="errorMessage"><?php if(isset($tabErrorMessage["confirmationMail"])){echo $tabErrorMessage["confirmationMail"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p class="pointer but_link" id="changeBtn" >Modifier les informations</p>
                                    <p class="pointer but_link" id="returnBtn">Annuler les modification</p>
                                    <input type="submit" class="but_link" name="submitProfile" id="submitProfile" value="Sauvegarder les modifications">
                                    <p class="pointer but_link" id="deleteProfileBtn" onclick="pop_cccAppear()">Supprimer votre compte</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>

                <!-- Partie historique des commande -->
                <div id="purchaseHistory">
                    <?php
                        if(empty($userTab['purchaseHistory']))
                        {
                            ?>
                                <div class="historyCommande" style="justify-content: center;">
                                    <h1>Historique de vos commandes</h1>
                                    <img id="logoPPP" src="./img/Panier.png" alt="Panier"> 
                                    <p id="vide">Votre historique est vide</p>
                                    <a href="./categorie.php?cat=all" class="pointer but_link">Voir tous nos produits</a>
                            <?php  
                        }
                        else{
                            ?> 
                            <div class="historyCommande">
                                <h1>Historique de vos commandes</h1>
                            <?php
                            $total = 0;
                            foreach($userTab['purchaseHistory'] as $sell)
                            {
                                $compt = 1;
                                $purchaseDateObj = new DateTime($sell["purchaseDate"]);
                                ?> 
                                    <h3>Commande du <?php echo date_format($purchaseDateObj, 'd/m/Y')." à ".date_format($purchaseDateObj, 'H:i:s');?></h3>
                                    <div class='command'>
                                <?php
                                foreach($sell["panier"] as $codepdt => $quantity)
                                {
                                    $total += setup_poppanprod($codepdt, $quantity, $compt); //ici on reutilise la fonction de setup des produits de la pop up panier, les id sont donc les memes attention dans le css
                                    $compt++;
                                }
                                ?>
                                    </div>
                                <?php
                            }
                            ?>
                                <a class="pointer but_link" id="but_removeHist" href="./profil.php?supHist=1">Effacer votre historique de commandes</a>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            </section>
        </main>
        <div id="pop_delProfile" style="display: none; "> <!-- pop up qui apparait si l'user n'est pas connecter au passage de la commande -->
            <img class="but_close" src="./img/croix.png" alt="croix.png" onclick="pop_cccAppear()">
            <div id="pop_delProfileContent">    
                <h3>Etes vous sur de supprimer votre compte ?</h3>
                <a href="./php/deleteProfileProcess.php" class="pointer but_link">Supprimer</a>
                <p class="pointer but_link" onclick="pop_cccAppear()">Annuler</p>
            </div>
        </div>
    </div>
    <?php
        include("./footer.php");
    ?>
    <script type="text/javascript" src="./js/script_manage_form.js"></script>
    <script type="text/javascript" src="./js/script_profil.js"></script>

</body>
</html>