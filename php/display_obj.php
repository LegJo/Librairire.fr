<?php
    require_once("./php/constant.php");
    require_once("./php/manage_files.php");

    function printAsideCat($tab) { // creer les lien vers les pages categories dans le aside
        foreach($tab as $key => $fields) {
            $src="./img/icons_cat/icon-".$fields.".png";
            if(!file_exists($src))
            {
                $src="./img/icons_cat/icon-Default.png";
            }
            ?>
            <a class="cat_link pointer" href="./categorie.php?cat=<?=$key;?>" id="link_<?=$key;?>">
                <img class="cat_icon" id="icon_<?=$key;?>" src=<?=$src;?> alt="•"></img>
                <p class="cat_name"><?=$fields;?></p>
            </a>
            <?php
        } 
    }

    function printHeaderCat($tab) {
        $max = 0;
        foreach($tab as $key => $fields) {
            if ($max != 3) {
                echo '<a class="onglet_nav" id="onglet_nav_'.$key.'" href="./categorie.php?cat='.$key.'">'.$fields.'</a>';
                $max++;
            }
        } 
    }

    function setupproduit(string $code_prod){ // permet de creer une boite contenant des infos sur le produit et un lien vers la page de ce produit
        $infos=getproductAllInfo($code_prod);
        $img=$infos['pic_name'];
        if($img=="erreur"){
            $img = "Ce produit ne possède pas d'image.";
        }else{
            $dossier_img = FILE_IMG;
            if (!file_exists($dossier_img) && !is_dir($dossier_img)){ 
                $img="Aucune image n'a été trouvée.";       
            }else{
                $verif=NULL;
                $dossier = opendir($dossier_img);
                while($fichier = readdir($dossier)){
                    if($fichier == $img){
                        $img='<a href="./produit.php?pdt='.$code_prod.'" id=linkpdt.'.$code_prod.'><img  class="img_boxproduit" alt="'.$img.'" src="'.FILE_IMG.$img.'"width="120px" height="120px"></img></a>';
                        $verif=$img;
                    }
                }closedir($dossier);
                if($img != $verif){
                    $img="Il n'y a pas d'image correspondant à ce produit.";
                }
            }
        }
        if($infos["quantite"]=="erreur"){
            return 0;
        }
        elseif($infos["prix"]=="erreur") {
            $infos['prix']="Prix indisponible pour le moment.";
        }
        echo '<div class="infos_boxproduit">'
        .$img.'<p>'.$infos['Titre'].' <br> ('.$infos['auteur'].')<br> '.$infos['description'].' 
        <br> Ref : '.$code_prod.' | Prix : '.$infos['prix'].'€ <br> <a href="./produit.php?pdt='.$code_prod.'" id=linkpdt.'.$code_prod.'>Page du produit</a>';
        if(isset($_SESSION["connected"]) && $_SESSION["connected"][1]==0){
                echo '<br> Quantité restante : '.$infos['quantite'];
        }
        echo  '</p></div>'; 
    }
 
    function setup_page_prod(string $code_prod){ //Permet de creer une page pour un produit avec toutes ses informations avec des boutons pour commander le produit
        $infos=getproductAllInfo($code_prod); 
        $code_cat=getTabCat();
        $categorie=0;
        foreach($code_cat as $key => $fields){
            if($infos['code_cat']==$key){
                $categorie=$fields; 
            }           
        }
        if($categorie=="0"){
            $categorie="Il n'y a pas de catégorie pour ce produit";
        }
        ?>
        <input class="but_link pointer retour" type="button" value="⇦ Retour" onclick=location.href="./categorie.php?cat=<?=$infos['code_cat'];?>"></input>
            <div class="page">
                <div class="image_box">
                    <img class="img_pdt" alt="<?= $infos['pic_name']; ?>" src="<?= FILE_IMG.$infos['pic_name']; ?>"></img>
                </div>
                <div>
                    <div class="achat">
                        <h2><?= $infos['Titre']; ?><br><?= $infos['auteur']; ?></h2> 
                        <hr>
                        <p>Prix :<?= $infos['prix']; ?>€</p>
                        <p id="stock"><script>stock_aff(<?= $infos['quantite']; ?>);</script></p>
                        <?php
                        if(isset($_SESSION["connected"]) && $_SESSION["connected"][1]==0){ // Si c'est un admin, on affiche le stock restant du produit
                            ?>
                            Quantité restante : <span id="affqt">
                                <?php 
                                if($infos['quantite']==0){
                                    echo 0;
                                }else{
                                    // echo $infos['quantite']-1;
                                    echo $infos['quantite'];
                                }
                                ?></span>
                            <?php
                        }?>
                        <br>
                        <div class="manage_quantity">
                            <input class="pointer" type="button" value="-" onclick="Valeur('-',<?= $infos['quantite']; ?>, '' )">
                            <p id="commande">1</p>
                            <input class="pointer" type="button" value="+" onclick="Valeur('+',<?= $infos['quantite']; ?>, '' )"> 
                        </div>  
                        <input class="but_link pointer acheter" type="button" value="Ajouter au panier" onclick="AjaxAddToCart('<?=$code_prod;?>')">                              
                    </div>
                    <div class="description">
                        <ul>
                            <li>Éditeur: <?= $infos['editeur']; ?></li>
                            <li>Catégorie: <?= $categorie; ?></li>
                            <li>Langue: <?= $infos['langue']; ?></li>
                            <li>ISBN-10: <?= $infos['ISBN-10']; ?></li>
                        </ul>
                    </div>
                </div>  
                </div>
                <div class="bas">
                    <div class="synopsis"><p><?= $infos['synopsis']; ?></p></div>
                    </div>
                </div>
        <?php  
    }

    function setup_best_Opport() //appel setupproduit pour le produit avec le moins de stock
    {
        $code_cat=getTabCat();
        if(count($code_cat)<1){
            echo "<h3> Il n'y a plus de produit en stock. </h3>";
        }
        $tmp=array();
        $tab=array();       
        $i=0;
        foreach($code_cat as $key => $fields){
            $tmp[$i]=$key;
            $i++;
        }
        $count=count($tmp);
        for($j=0;$j<$count;$j++){
            $tab[$j]=getcategorieproduct($tmp[$j]);
        }
        $size=count($tab);
        $k=0;
        for($i=0;$i<$size;$i++){
            $count=count($tab[$i]);
            for($j=0;$j<$count;$j++){
                $produit[$k]=$tab[$i][$j];
                $k++;
            }  
        }
        for($i=0;$i<$k ;$i++){
            $quantite[$i]=(string) getproductInfo($produit[$i], "quantite");
            if($quantite[$i]==0){$quantite[$i]="erreur";}
        }
        $err=0;
        for($i=0;$i<$k ;$i++){
            if($quantite[$i]=="erreur" || $quantite[$i]==0) {
                $err=($err + 1);
            } 
        }
        if($err==($k)){
            ?>
            <h2> Attention, il n'y a plus de produit en stock. </h2>
            <?php 
        }else{
            array_multisort($quantite,$produit);
            setupproduit($produit[0]);  
        } 
    }

    function setupPage_cat(string $code_cat){ //  a partir d'un code catégorie, créé une zone de vente pour tous les produits appartenant à cette catégorie
        $temp=getcategorieproduct($code_cat);
        $count=sizeof($temp);
        $err=0;
        if($count>0){
            for($i=0;$i<$count;$i++){
                $quant[$i]=getproductInfo($temp[$i], "quantite");
            }
            for($i=0;$i<$count;$i++){
                if($quant[$i]=="erreur"){
                    $err=$err+1;
                }
                if($err==($count)){
                 ?>
                    <h2> Attention, il n'y a plus de stock pour cette catégorie.  </h2>
                 <?php 
                }else{        
                    setupproduit($temp[$i]);
                }
            } 
        }else{
            ?>
            <h3> Aucun produit n'a été trouvé dans cette catégorie. </h3>
            <?php
        }    
    }

    function setup_panprod(string $code_prod, int $quantity, int $num) //display 1 produit pour le panier de la page panier, avec le code produit et la quantité commander
    {
        $infos=getproductAllInfo($code_prod);
        if($infos['Titre'] != "Not Founded")
        {
            $price=$quantity * $infos['prix'];
            $qtTotal= intval($infos['quantite']) + intval($_SESSION['panier'][$code_prod]);
            ?>
            <div class="product_pan" id="product<?=$num;?>" name="div<?=$code_prod;?>">
                <a href="./produit.php?pdt=<?=$code_prod;?>" id="linkpdt<?=$code_prod;?>">
                    <img class="pp_img pp_child pointer" src="./img/img_products/<?=$infos['pic_name'];?>" alt="<?=$infos['pic_name'];?>" >
                </a>
                <div class="product_info pp_child">
                    <h2><?=$infos['Titre'];?><br></h2>
                    Description : <?=$infos['description'];?><br>
                    <span name="txt<?=$code_prod;?>">
                    Prix : <?=$price;?>€<br>
                    Quantité : <?=$quantity;?> 
                    </span>
                    
                </div>
                <div class="manage_product pp_child">
                    <input class="but_link pointer" type="button" value="Supprimer" onclick=" AjaxRemProd('<?=$code_prod;?>');">
                    <div class="manage_quantity">
                        <input class="pointer" type="button" value="-" onclick=" AjaxChQtInCart('<?=$code_prod;?>', 'minus',<?=$qtTotal;?>); Valeur('-',<?=$qtTotal;?>,'<?=$code_prod;?>')">
                        <p id="commande<?=$code_prod;?>"><?=$quantity;?></p>
                        <input class="pointer" type="button" value="+" onclick=" AjaxChQtInCart('<?=$code_prod;?>', 'plus',<?=$qtTotal;?>); Valeur('+',<?=$qtTotal;?>,'<?=$code_prod;?>')"> 
                    </div>
                    <a href="./produit.php?pdt=<?=$code_prod;?>" class="but_link pointer enabled">Voir la page du produit</a>
                </div>
            </div>
            <?php  
            return $price;
        }
        else
        {
            unset($_SESSION['panier'][$code_prod]);
            if(empty($_SESSION['panier']))
            {
                unset($_SESSION['panier']);
                header("Location: ./panier.php", true, 301);
            }
            return 0;
        }
    }

   

    function setup_pan() //affiche le panier de la page panier en fonction du $_SESSION['panier']
    {
        $total=0;
        if(!isset($_SESSION['panier']))
        {
            ?>
                <div id="pan_footer">
                    <img id="logoPV" src="./img/Panier.png" alt="Panier"> 
                    <p>Votre panier est vide</p>
                    <a href="./categorie.php?cat=all" class="pointer but_link">Voir tous nos produits</a>
                </div>
            <?php  

        }
        else
        {
            $compt = 1;
            foreach($_SESSION['panier'] as $codepdt => $quantity)
            {
                $total += setup_panprod($codepdt, $quantity, $compt);
                $compt++;
            }
        }
        setup_butbotpan($total);
    }

    function setup_butbotpan($total) //genere le bas du panier de la page panier 
    {
        if(!isset($_SESSION['panier']) || $total == 0)
        {
            $_SESSION['errormsg']="impossible to setup bottom panier";
            return;exit;
        }
        else
        {
            ?>
              <div id="pan_footer">
                Total Panier :<span id="priceTot"><?=$total;?></span>€
                <a href="./php/commandProcess.php?pro=1" class="pointer but_link">Passer la commande </a>
              </div>
            <?php
        }
    }

    function setup_poppanprod(string $code_prod, int $quantity, int $num) //display 1 produit pour le panier du POPUP panier du header, avec le code produit et la quantité commander
    {
        $infos=getproductAllInfo($code_prod);
        if($infos['Titre'] != "Not Founded")
        {
            $price=$quantity * $infos['prix'];
            ?>
            <div class="product_poppan <?=$code_prod;?>" id="popproduct<?=$num;?>">
                <a href="./produit.php?pdt=<?=$code_prod;?>" id="linkpdt<?=$code_prod;?>">
                    <img class="ppp_img ppp_child pointer" src="./img/img_products/<?=$infos["pic_name"];?>" alt="<?=$infos["pic_name"];?>">
                </a>
                <div class="popproduct_info ppp_child" id="qt<?=$code_prod;?>">
                    <h4><?= $infos["Titre"]; ?></h4>
                    <?= $infos["prix"]; ?>€ x<?=$quantity;?>         
                </div>
            </div>
            <?php
            return $price;
        }
        else
        {
            unset($_SESSION['panier'][$code_prod]);
            if(empty($_SESSION['panier']))
            {
                unset($_SESSION['panier']);
                
                header("Location: ". $_SERVER['REQUEST_URI'], true, 301);
            }
            return 0;
        }
    }

    
    function setup_poppan() //affiche le panier du POPUP panier du header en fonction du $_SESSION['panier']
    {
        
        $total=0;         
        if(!isset($_SESSION['panier']) || empty($_SESSION['panier']))
        {
            ?>
                <div id="ppp_content" class="popup_content" style="justify-content: center;">
                    <img id="logoPPP" src="./img/Panier.png" alt="Panier"> 
                    <p id="vide">Votre panier est vide</p>
            <?php  
        }
        else
        {
            $compt = 1;
            ?> 
                <div id="ppp_content" class="popup_content" style="justify-content: flex-start;"> 
            <?php
            foreach($_SESSION['panier'] as $codepdt => $quantity)
            {
                $total += setup_poppanprod($codepdt, $quantity, $compt);
                $compt++;
            }
        }
        ?>
            <a href="./panier.php" class="pointer but_link" id="but_panier">Voir votre panier</a>
        <?php
    }

    function setup_popconnect() //generation du contenue de la pop up connection en fonction du user => code user contenue dans $_SESSION['connected'] qd le user est connecter
    {
        if(isset($_SESSION['connected']))
        {
            $code_user=$_SESSION['connected'];
            if($code_user[1]==1)
            {
              ?>
                <div id="ppc_content" class="popup_content">
                    <img id="logoPPC" src="./img/Client.png" alt="Se connecter/S'inscrire"> 
                    <p><?=getUserData($code_user, "name");?> <?=getUserData($code_user, "firstName");?></p>
                    <div id="div_but_connect">
                        <a href="./profil.php" class="pointer but_link">Votre Profil</a>
                        <a href="./php/deconnexion.php" class="pointer but_link">Se Deconnecter</a>
                    </div>
                </div>
              <?php
            }
            else if($code_user[1]==0)
            {
              ?>
               <div id="ppc_content" class="popup_content">
                    <img id="logoPPC" src="./img/Client.png" alt="Se connecter/S'inscrire"> 
                    <p>Admin : <?=getUserData($code_user, "name");?> <?=getUserData($code_user, "firstName");?></p>
                    <div id="div_but_connect">
                        <a href="./profil.php" class="pointer but_link">Votre Profil</a>
                        <a href="./backOffice.php" class="pointer but_link">Back Office</a>
                        <a href="./mailSending.php" class="pointer but_link">Envoi de mail</a>
                        <a href="./php/deconnexion.php" class="pointer but_link">Se Deconnecter</a>
                    </div>
                </div>
              <?php
            }
            else
            {
                $_SESSION['errormsg']="$code_user, invalid user code";
                return;exit;
            }

        }
        else
        {
          ?>
            <div id="ppc_content" class="popup_content">
                <img id="logoPPC" src="./img/Client.png" alt="Se connecter/S'inscrire"> 
                <div id="div_but_connect">
                    <a href="./login.php" class="pointer but_link">Se Connecter</a>
                    <a href="./createAccount.php" class="pointer but_link">S'inscrire</a>
                </div>
            </div>
          <?php
        }
    }

    function setup_fact() //affiche le panier de la page validPanier en fonction du $_SESSION['panier']
    {
        $total=0;
        if(!isset($_SESSION['panier']))
        {
            ?>
                <tbody>
                    <tr>
                        <td colspan="6">Vous n'avez aucuns articles</td>
                    </tr>
                </tbody>
                <a href="./categorie.php?cat=all" class="pointer but_link">Voir tous nos produits</a>
            <?php  

        }
        else
        {
            ?>
                <tbody>
            <?php
            $compt = 1;
            foreach($_SESSION['panier'] as $codepdt => $quantity)
            {
                $total += setup_factprod($codepdt, $quantity, $compt); //recupère le prix TTC en fonction de la quantité du produit
                $compt++;
            }
            ?> 
                </tbody>
                <tfoot> <!--affiche le prix HT, la TVA, le prix TTC comme footer-->
                    <tr>
                        <td colspan="4">Prix HT :</td>
                        <td colspan="2">
                            <?php
                                $tva = $total*(0.055);
                                $ttc = $total-$tva;
                                $ttc = round($ttc, 2);
                                echo $ttc;
                            ?>€
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">TVA (+5,5%) :</td>
                        <td colspan="2">
                            <?php
                                $tva = round($tva, 2);
                                echo $tva;
                            ?>€
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">Prix TTC :</td>
                        <td colspan="2"><?=$total;?>€</td>
                    </tr>
                </tfoot>
            <?php 
        }
    }

    function setup_factprod(string $code_prod, int $quantity, int $num) //display 1 produit pour le tableau de pages validPanier et facture et renvoie le prix TTC en fonction de la quantité du produit
    {
        $infos=getproductAllInfo($code_prod);
        $price=$quantity * $infos['prix'];
        
        ?>
            <tr class="product_info" id="product<?=$num;?>">
                <td><?=$infos['Titre'];?></td>
                <td><?= $infos['prix'];?>€</td>
                <td>
                    <?php
                        $tva_unit = $infos['prix']*(0.055);
                        echo  round($tva_unit, 2);
                    ?>€<br>
                </td>
                <td><?=$quantity;?></td>
                <td><?=$price;?>€<br></td>
                <td>
                    <?php
                        $tva = $price*(0.055);
                        echo round($tva, 2);
                    ?>€<br>
                </td>
            </tr>
        <?php  
        return $price;
    }

    function printCatOffice($tab) { 
        foreach($tab as $key => $fields) {
            echo '<option id="'.$key.'" value="'.$key.'" onclick="Affiche_produits(value)">'.$fields.'</option>';
        }
    }

    function printProduitOffice($tab, $cat) {
        foreach($tab as $code) {
            $titre = getproductInfo($code, "Titre");
            foreach($titre as $key => $fields) {
                echo '<option style="display: none;" class="'.$cat.' produits" id="'.$code.'" value="'.$code.'" onclick="Affiche_details(value,'.$cat.')">'.$fields.'</option>';
            }
        }
    }

    function printDetailOffice($produit) {
        $prod = getproductAllInfo($produit);
        foreach($prod as $key => $fields) {
            echo '<option style="display: none;" class="'.$produit.' infos" id="'.$key.'" value="'.$fields.'" title=""  onclick="Form_details(value, id, '.$produit.', title)">'.$key.'</option>';
        }
    }
?>