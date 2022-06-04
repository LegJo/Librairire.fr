<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/createAccount.css">
    <title>Librairire</title>
</head>
<body>
    <?php 
        session_start();

        if(!empty($_SESSION['errorMessageRegistration']))
        {
            $tabErrorMessage = [];
            foreach($_SESSION['errorMessageRegistration'] as $errorKey => $error) {
                $tabErrorMessage[$errorKey] = $error;
            }
            unset($_SESSION['errorMessageRegistration']);
        }

        if(!empty($_SESSION["correctInputRegistration"]))
        {
            $tabCorrectInput = [];
            foreach($_SESSION['correctInputRegistration'] as $inputName => $input) {
                $tabCorrectInput[$inputName] = $input;
            }
            unset($_SESSION["correctInputRegistration"]);
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
        
                <?php
                    /*
                    $nbError = 0;
                    if(isset($_SESSION['errorMessageRegistration']) && !empty($_SESSION['errorMessageRegistration']))
                    {
                        foreach($_SESSION['errorMessageRegistration'] as $errorName => $error) {
                            if(!empty($error)) {
                                $nbError++;
                            }
                        }

                        if($nbError > 0)
                        {
                            echo "<div id=\"inputErrors\">";
                            echo "<ul>";
                            foreach($_SESSION['errorMessageRegistration'] as $errorName => $error) {
                                if($error != ""){
                                    echo "<li>$error</li>";
                                }
                            }
                            echo "</ul>";
                            echo "</div>";
                        }
                        unset($_SESSION['errorMessageRegistration']);
                    }
                    */

                ?>
                
                <form  id="form-registration" onsubmit="return (checkingForm('registration'))" action="./php/createAccountProcess.php" method="post">
                    <div id="formulaire">    
                        <h1>Inscription</h1>
                        <table>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="name">Nom</label>
                                        <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="name" id="name" class="<?=$classValid?>" required placeholder="Nom" size="32" value="<?php if(isset($tabCorrectInput["name"])){echo $tabCorrectInput["name"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-name" class="errorMessage"><?php if(isset($tabErrorMessage["name"])){echo $tabErrorMessage["name"];}else{echo "";} ?></p>
                                    </div> 
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="firstName">Prénom</label>
                                        <?php $classValid = (!empty($tabErrorMessage["firstName"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="firstName" id="firstName" class="<?=$classValid?>" required placeholder="Prénom" size="32" value="<?php if(isset($tabCorrectInput["firstName"])){echo $tabCorrectInput["firstName"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-firstName" class="errorMessage"><?php if(isset($tabErrorMessage["firstName"])){echo $tabErrorMessage["firstName"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1">
                                    <div class="input_label">
                                        <label for="sexChoice">Sexe</label> 
                                        <?php $classValid = (!empty($tabErrorMessage["sex"])) ? 'invalidInput' : '' ?>
                                        <div id="sexChoice">
                                            <input type="radio" id="sexMan" class="<?=$classValid?>" name="sex" value="Man" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Man"){echo "checked";}else{echo "";} ?>>
                                            <label for="sexMan">Homme</label>
                                        
                                            <input type="radio" id="sexWoman" class="<?=$classValid?>" name="sex" value="Woman" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Woman"){echo "checked";}else{echo "";} ?>>
                                            <label for="sexWoman">Femme</label>

                                            <input type="radio" id="sexOther" class="<?=$classValid?>" name="sex" value="Other" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Other"){echo "checked";}else{echo "";} ?>>
                                            <label for="sexOther">Autre</label>
                                        </div>  
                                        <p id="errorMessage-registration-sex" class="errorMessage"><?php if(isset($tabErrorMessage["sex"])){echo $tabErrorMessage["sex"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="birthDate">Date de naissance</label>
                                        <?php $classValid = (!empty($tabErrorMessage["birthDate"])) ? 'invalidInput' : '' ?>
                                        <input type="date" name="birthDate" id="birthDate" class="<?=$classValid?>" required value="<?php if(isset($tabCorrectInput["birthDate"])){echo $tabCorrectInput["birthDate"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-birthDate" class="errorMessage"><?php if(isset($tabErrorMessage["birthDate"])){echo $tabErrorMessage["birthDate"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="mail">Adresse mail</label>
                                        <?php $classValid = (!empty($tabErrorMessage["mail"])) ? 'invalidInput' : '' ?>
                                        <input type="email" name="mail" id="mail" class="<?=$classValid?>" placeholder="Adresse mail" required size="25" value="<?php if(isset($tabCorrectInput["mail"])){echo $tabCorrectInput["mail"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-mail" class="errorMessage"><?php if(isset($tabErrorMessage["mail"])){echo $tabErrorMessage["mail"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="confirmationMail">Confirmation mail</label>
                                        <?php $classValid = (!empty($tabErrorMessage["confirmationMail"])) ? 'invalidInput' : '' ?>
                                        <input type="email" name="confirmationMail" id="confirmationMail" class="<?=$classValid?>" required placeholder="Adresse mail" size="25" value="<?php if(isset($tabCorrectInput["confirmationMail"])){echo $tabCorrectInput["confirmationMail"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-confirmationMail" class="errorMessage"><?php if(isset($tabErrorMessage["confirmationMail"])){echo $tabErrorMessage["confirmationMail"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="password">Mot de passe</label>
                                        <?php $classValid = (!empty($tabErrorMessage["password"])) ? 'invalidInput' : '' ?>
                                        <input type="password" name="password" id="password" class="<?=$classValid?>" required placeholder="Mot de passe">
                                        <p id="errorMessage-registration-password" class="errorMessage"><?php if(isset($tabErrorMessage["password"])){echo $tabErrorMessage["password"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="confirmationPassword">Confirmation mot de passe</label>
                                        <?php $classValid = (!empty($tabErrorMessage["confirmationPassword"])) ? 'invalidInput' : '' ?>
                                        <input type="password" name="confirmationPassword" id="confirmationPassword" class="<?=$classValid?>" required placeholder="Mot de passe">
                                        <p id="errorMessage-registration-confirmationPassword" class="errorMessage"><?php if(isset($tabErrorMessage["confirmationPassword"])){echo $tabErrorMessage["confirmationPassword"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="input_label">
                                        <label for="address">Adresse</label>
                                        <?php $classValid = (!empty($tabErrorMessage["address"])) ? 'invalidInput' : '' ?>
                                        <textarea class="adresse" name="address" id="address" class="<?=$classValid?>" required rows="3" ><?php if(isset($tabCorrectInput["address"])){echo $tabCorrectInput["address"];}else{echo "";} ?></textarea>
                                        <p id="errorMessage-registration-address" class="errorMessage"><?php if(isset($tabErrorMessage["address"])){echo $tabErrorMessage["address"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="postalCode">Code postal</label>
                                        <?php $classValid = (!empty($tabErrorMessage["postalCode"])) ? 'invalidInput' : '' ?>
                                        <input type="number" name="postalCode" id="postalCode" class="<?=$classValid?>" required placeholder="Code postal" min="00000" max="99999" class="bouton" value="<?php if(isset($tabCorrectInput["postalCode"])){echo $tabCorrectInput["postalCode"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-postalCode" class="errorMessage"><?php if(isset($tabErrorMessage["postalCode"])){echo $tabErrorMessage["postalCode"];}else{echo "";} ?></p> 
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="city">Ville</label>
                                        <?php $classValid = (!empty($tabErrorMessage["city"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="city" id="city" class="<?=$classValid?>" placeholder="Ville" required value="<?php if(isset($tabCorrectInput["city"])){echo $tabCorrectInput["city"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-city" class="errorMessage"><?php if(isset($tabErrorMessage["city"])){echo $tabErrorMessage["city"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="input_label">
                                        <label for="phoneNumber">Numéro de Téléphone</label>
                                        <?php $classValid = (!empty($tabErrorMessage["phoneNumber"])) ? 'invalidInput' : '' ?>
                                        <input type="tel" name="phoneNumber" id="phoneNumber" class="<?=$classValid?>" required placeholder="Téléphone" value="<?php if(isset($tabCorrectInput["phoneNumber"])){echo $tabCorrectInput["phoneNumber"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-phoneNumber" class="errorMessage"><?php if(isset($tabErrorMessage["phoneNumber"])){echo $tabErrorMessage["phoneNumber"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="input_label">
                                        <label for="job">Métier</label>
                                        <?php $classValid = (!empty($tabErrorMessage["job"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="job" id="job" class="<?=$classValid?>" placeholder="Métier" required value="<?php if(isset($tabCorrectInput["job"])){echo $tabCorrectInput["job"];}else{echo "";} ?>">
                                        <p id="errorMessage-registration-job" class="errorMessage"><?php if(isset($tabErrorMessage["job"])){echo $tabErrorMessage["job"];}else{echo "";} ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" class="but_link" name="submitRegistration" value="Valider inscription">
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
    <script type="text/javascript" src="./js/script_manage_form.js"></script>
</body>
</html>