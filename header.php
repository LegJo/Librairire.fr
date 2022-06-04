<header>
    <div class="header_child">
        <a href="./index.php">
            <img id="logo_h" class="logo_L" src="./img/Logo_Librairire.png" alt="logo">
        </a>
        <div id="buttonAA" class="main_child pointer" onclick="AsideAppear()">
                Catégories
        </div>
    </div>
    <div class="header_child">
        <img id="header_title" src="./img/Librairire.png" alt="Librairire"> <!--image titre au centre -->
        <!--onglet nav sous le titre  -->
        <nav id="header_nav"> 
            <a class="onglet_nav" href="./index.php">
                Accueil
            </a>
            <a class="onglet_nav" href="./categorie.php?cat=all">
                Produits
            </a>
            <a class="onglet_nav" href="./aPropos.php">
                À Propos
            </a>
            <a class="onglet_nav" href="./contact.php">
                Contact
            </a>
        </nav>
    </div>
    <!-- "bouton" connexion -->
    <div class="header_child menu_h pointer" id="menu_connexion" onclick="ConnectAppear()">
        <?php
            require_once('./php/display_obj.php');
            if(isset($_SESSION['connected']))
            {?>
                <p><?=getUserData($_SESSION['connected'], "name");?> <?=getUserData($_SESSION['connected'], "firstName");?></p>
            <?php
            }
            else
            {?>
                <p>Connexion</p>
            <?php
            }
        ?>
        <img class="icon_nav" id="logo_user" src="./img/Client.png" alt="Se connecter/S'inscrire">
    </div>
    <!-- popup de connexion -->
    <div id="popup_connect" class="popup_h">
        <img class="but_close" src="./img/croix.png" alt="x" onclick="ConnectAppear()">
        <?php
            setup_popconnect();
        ?>
    </div>
    <!-- "bouton" panier -->
    <div class="header_child menu_h pointer" id="menu_panier" onclick="PanierAppear()">
        <p>Panier</p>
        <img class="icon_nav" id="logo_panier" src="./img/Panier.png" alt="Panier"> 
    </div>
    <!-- popup du panier -->
    <div id="popup_panier" class="popup_h">
        <img class="but_close" src="./img/croix.png" alt="x" onclick="PanierAppear()">
        <?php
            setup_poppan();
        ?>
        </div>
    </div>
</header>