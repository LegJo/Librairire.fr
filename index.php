<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/index.css">
    <script src="./js/script_indexanim.js"></script>
    <title>Librairire</title>
</head>
<body>
    <?php 
        session_start();
        require_once("./php/display_obj.php");
        require_once("./php/manage_files.php");
        include("./header.php");
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" class="main_child"> 
                <div id='warped'>
                    <!--l'arrondie du texte a ete cree avec l'aide d'un generateur en ligne, voir le css dans css/index.css pour lien-->
                    <span id='w0'>B</span><span id='w1'>I</span><span id='w2'>E</span><span id='w3'>N</span><span id='w4'>V</span><span id='w5'>E</span><span id='w6'>N</span><span id='w7'>U</span><span id='w8'>E</span><span id='w9'> </span><span id='w10'>S</span><span id='w11'>U</span><span id='w12'>R</span><span id='w13'> </span><span id='w14'>L</span><span id='w15'>I</span><span id='w16'>B</span><span id='w17'>R</span><span id='w18'>A</span><span id='w19'>I</span><span id='w20'>R</span><span id='w21'>I</span><span id='w22'>R</span><span id='w23'>E</span><span id='w24'>.</span><span id='w25'>F</span><span id='w26'>R</span>
                    <!--  -->
                    <img id="logo_ind" class="pointer" src="./img/Logo_Librairire.png" alt="logo" onclick="WelcomAnimation();">
                </div>
                <a href="./categorie.php?cat=all" class="but_link pointer" id="but_allprod">
                    <!-- bouton défilant avec les produits -->
                    Tous nos Produits
                    <?php
                        $tabProd = getAllProducts(); //tableau avec tout les code produit 
                        if(shuffle($tabProd)) // mélange du tableau 
                        {
                            echo "<div id='imgProds'>";
                            for($i=0; $i<count($tabProd); $i++)
                            {
                                $pathImg = getproductInfo($tabProd[$i], "pic_name");//recup du path des img des produits 
                                if($pathImg != "default.png")
                                {
                                    ?>
                                        <img src="./img/img_products/<?=$pathImg;?>" alt="<?=$tabProd[$i];?>" id="img<?=$tabProd[$i];?>">
                                    <?php
                                }
                            }
                            echo "</div>";
                        }
                    ?>
                </a>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
    <script>
        WelcomAnimation();
        SlideShowProd();
    </script>
</body>
</html>