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
        require_once("./php/display_obj.php");
        include("./header.php");

        if(!empty($_SESSION['errorMessageLogin']))
        {
            $errorMessage = $_SESSION['errorMessageLogin'];
            unset($_SESSION['errorMessageLogin']);
        }
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" class="main_child">
                <form action="./php/loginProcess.php" method="post">
                    <div id="formulaire">    
                        <h1>Connexion</h1>
                            <?php $classValid = (!empty($errorMessage)) ? 'invalidInput' : '' ?>
                            <div class="input_label">
                                <label for="mail">Adresse mail</label>
                                <input type="email" name="mail" id="mail" class="<?=$classValid?>" placeholder="Adresse mail" required>
                            </div><br>
                            <div class="input_label">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" id="password" class="<?=$classValid?>" placeholder="Mot de passe" required>
                            </div>
                            <p id="errorMessage-login-name" class="errorMessage"><?php if(isset($errorMessage)){echo $errorMessage;}else{echo "";} ?></p>
                            <div class="input_label">
                                <input class="but_link pointer" type="submit" name="submitLogin" value="M'identifier">
                            </div>
                    </div>  
                </form>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>