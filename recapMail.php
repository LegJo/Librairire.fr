<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/mailSending.css">
    <title>Votre Mail</title>
</head>
<body>
    <?php
        session_start();
        include("./header.php");
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" class="main_child"> 
                <div id="div_recapMail">
                    <?php
                        if(isset($_SESSION['mail']))
                        {
                            echo "<h1>Mail envoyé ✓</h1>";
                            echo $_SESSION['mail'];
                            unset($_SESSION['mail']);
                        }
                        else
                        {
                            header("Location: ./mailSending.php", true, 301);
                            exit;
                        }
                    ?>
                </div>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>