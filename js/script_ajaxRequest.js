function getXhr()//cree objetXHR
{
    if (window.XMLHttpRequest) // Pour la majorité des navigateurs
        return new XMLHttpRequest() ;
    else if(window.ActiveXObject){ // Anciens navigateurs (IE<7)
        try {
            return new ActiveXObject("Msxml2.XMLHTTP") ;
        } catch (e) {
            return new ActiveXObject("Microsoft.XMLHTTP") ;
        }
    }
    else { // XMLHttpRequest non supporté par le navigateur
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...") ; 
    }
    return NULL ;
}

function GetComProdPage($code_pdt){ //fonction qui recupere la quantité de commander sur la page 
    var commande = document.getElementById("commande"+$code_pdt).innerHTML;
    return commande;
}

/*Fonction d'envoie de requetes Ajax*/

function AjaxAddToCart(code_prod){ //creer requete ajax qui actualise un le stock dans les fichiers sur le serveur et $_SESSION['panier'] en ajoutant une quantité de produit (dans les pages produits notamment)
    var qtcmd=0-parseInt(GetComProdPage(''));
    var requestCartA= getXhr();
    requestCartA.open("POST","./php/ajaxRequest.php",true);
    requestCartA.onreadystatechange = function(){
        if(requestCartA.readyState == 4 && requestCartA.status == 200){
            var reponse=requestCartA.responseText;
            if(reponse==0){
                return 0;
            }
            var res=reponse.split(";");
            popup_add_pan(qtcmd,res[1],res[3]);
            change_aff_quantity(res[0]);
            stock_aff(parseInt(res[0]));
            maj_pop(code_prod,res[2],res[3],res[4],res[5]);
            if(document.getElementById("popup_panier").style.display != "flex"){
                PanierAppear();
            }
            //Afficher ces console.log pour mieux comprendre
            /*console.log("qtt act dans file :"+ res[0]);
            console.log("qtt commander reussi :"+res[1]);
            console.log("qtt act dans le session :"+res[2]);
            console.log("nom du bouquin :"+ res[3]);*/
        }
    }
    requestCartA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
    requestCartA.send('fct=addpdt' + '&codepdt=' + code_prod + '&qtcmd=' + qtcmd);
    return 0;    
}

function AjaxChQtInCart(code_prod, sign, qttot) //requete ajax pour les boutons + - dans le fichier panier.php => actualise la quantité dans les fichiers et dans le session panier
{
    var quantity=parseInt(GetComProdPage(code_prod));
    // console.log(quantity);
    var requestCartC= getXhr();
    requestCartC.open("POST","./php/ajaxRequest.php",true);
    requestCartC.onreadystatechange = function(){
        if(requestCartC.readyState == 4 && requestCartC.status == 200){
            var reponse=requestCartC.responseText;
            var res=reponse.split(";");
            if(quantity<=1 && sign=="minus"){
                return 0;
            }else if(res[0]<=0 && sign=="plus"){
                if(qttot == res[2]){
                    maj_pop(code_prod,res[2],res[3],res[4],res[5]);
                    modif_qt_price(code_prod, res[2], res[4], sign);
                }else{
                    return 0;
                }
            }else{
                maj_pop(code_prod,res[2],res[3],res[4],res[5]);
                modif_qt_price(code_prod, res[2], res[4], sign);
            }
            //Afficher ces console.log pour mieux comprendre
            /*console.log("qtt act dans file :"+ res[0]);
            console.log("qtt add/retire dans file:" + res[1]);
            console.log("qtt act dans le session :" + res[2]);
            console.log(res[3]);
            console.log("-------------------");*/
        }
    }
    requestCartC.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
    requestCartC.send('fct=chgqt' + '&codepdt=' + code_prod + '&qt=' + quantity + '&sign=' + sign);
    return 0;    
}

function AjaxRemProd(code_prod) //requete ajax pour le bouton supprimer dans le fichier panier.php => actualise la quantité dans les fichiers et dans le session panier
{
    var quantity=parseInt(GetComProdPage(code_prod));
    // console.log(quantity);
    var requestCartR= getXhr();
    requestCartR.open("POST","./php/ajaxRequest.php",true);
    requestCartR.onreadystatechange = function(){
        if(requestCartR.readyState == 4 && requestCartR.status == 200){
            var reponse=requestCartR.responseText;
            var res=reponse.split(";");
            sup_intot_price(code_prod, res[4]);
            maj_pop(code_prod, res[2], res[3], res[4], res[5]);
            sup_in_pan(code_prod);
            //Afficher ces console.log pour mieux comprendre
            /*console.log("qtt act dans file :"+ res[0]);
            console.log("qtt add/retire dans file:" + res[1]);
            console.log("qtt act dans le session :" + res[2]);
            console.log(res[3]);
            console.log("-------------------");*/
        }
    }
    requestCartR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
    requestCartR.send('fct=remprod' + '&codepdt=' + code_prod + '&qt=' + quantity);
    return 0;    
}

/*-------------*/

function popup_add_pan(commande, panier, Nom){ //affiche une popup en fonction de la commande lors de l'ajout au panier
    var main = document.getElementsByTagName('main');
    var maininputs = main[0].getElementsByTagName('input');
    for (let i = 0; i < maininputs.length; i++) {
        maininputs[i].disabled = !maininputs[i].disabled;//Fait en sorte que les boutons en arrière plan ne fonctionnent pas lorsque la popup est affichée
    }
    if (commande < 0) {
        commande = -commande;
    }
    var pop_add = document.getElementById("valid_achat");
    var titre= document.getElementById("titre");
    var resume = document.getElementById("resume");
    if(panier==commande){
        titre.innerHTML="Et voilà ! C'est ajouté dans votre panier";
        resume.innerHTML="Vous avez commandé : "+ Nom +" x "+ panier;
    }else if(panier<commande && panier>0){
        titre.innerHTML="Et voilà ! C'est ajouté dans votre panier";
        resume.innerHTML="Mais le stock n'étant pas assez important nous n'avons pu ajouter à votre panier que : "+ Nom +" x "+ panier;
    }else{
        titre.innerHTML="Désolé, il n'y a plus de stock, nous ne pouvons pas ajouter ce produit à votre panier.";
        resume.innerHTML="";
    }
    pop_add.style.display="flex";
    document.getElementsByTagName("main")[0].style.filter="brightness(30%)";
    return 0;
}

function change_aff_quantity(stock){ // Si c'est un admin, affiche le stock restant en temps réel et remet la commande à 1
    var qt = document.getElementById("affqt");
    var testAdmin=!!document.getElementById("affqt");
    if(testAdmin==true){
        console.log('test'+stock);
        qt.innerHTML= stock;
        if(stock==0){
            qt.innerHTML= 0;
        }else{
            qt.innerHTML= stock;
        }
    }
    var commande = document.getElementById("commande");
    commande.innerHTML=1;
}

function animeQt_chg(elmt, colr)//Change la couleur du texte du produit dont la quantité est modifiée
{
    elmt.style.color=colr;
}

function maj_pop(code_prod,Nqt,Titre,prix,img){ // Permet de mettre a jour la popup panier en temps reelle, sans attendre un refresh et donc sans le resetup de la popup avec le $SESSSION panier actualiser
    var content=document.getElementById("ppp_content");
    var count = document.getElementById("ppp_content").childElementCount;
    var monprod=content.getElementsByClassName("product_poppan "+code_prod);
    if (Nqt == 0) {
        if(document.getElementById("stock") != undefined){//Fait la différence entre la page produit et le panier
            return 0;
        }else if (monprod.length == 1) {
            content.removeChild(monprod[0]);
            count = document.getElementById("ppp_content").childElementCount;
            if (count == 1) {
                setup_empty_pan();
            }
        }
    }else if(count==3){
         if(content.style.justifyContent=="flex-start"){// il y a des éléments dans le panier
            if(typeof monprod === undefined){
                Add_new_to_pan(code_prod,Nqt,Titre,prix,img,count);
            }
            else if(monprod.length==1){ //l'élément existe déjà, on modifie juste la quantité commandée
                var modqt=document.getElementById("qt"+code_prod);
                modqt.innerHTML="";
                var h4 = document.createElement("h4");
                h4.innerHTML=Titre;
                modqt.appendChild(h4);
                modqt.innerHTML+=prix+"€ x "+Nqt;
                animeQt_chg(modqt, "green");
            }else{
                Add_new_to_pan(code_prod,Nqt,Titre,prix,img,count);
            }
        }
        else{ // il n'y a aucun élément dans le panier
            var image=document.getElementById("logoPPP");
            var texte=document.getElementById("vide");
            content.style.justifyContent="flex-start";
            content.removeChild(image);
            content.removeChild(texte);
            count=1;
            Add_new_to_pan(code_prod,Nqt,Titre,prix,img,count);
        }
    }
    else{
        if(typeof monprod === undefined){
            Add_new_to_pan(code_prod,Nqt,Titre,prix,img,count);
        }
        else if(monprod.length==1){
            var modqt=document.getElementById("qt"+code_prod);
            modqt.innerHTML="";
            var h4 = document.createElement("h4");
            h4.innerHTML=Titre;
            modqt.appendChild(h4);
            modqt.innerHTML+=prix+"€ x "+Nqt;
            animeQt_chg(modqt, "green");
        }else{
            Add_new_to_pan(code_prod,Nqt,Titre,prix,img,count);
        }
    }
}

function Add_new_to_pan(code_prod,Nqt,Titre,prix,img,count){ // permet d'ajouter un produit au panier lorsqu'il n'y est pas déjà
    var content=document.getElementById("ppp_content");
    var button=document.getElementById("but_panier");
    content.removeChild(button);

    var produit = document.createElement("div");
    produit.id="popproduct"+(count);
    produit.className="product_poppan "+code_prod;
    content.appendChild(produit);

    var lien = document.createElement("a");
    lien.href="./produit.php?pdt="+code_prod;
    lien.id="linkpdt"+code_prod;

    var image = document.createElement("img");
    image.className="ppp_img ppp_child pointer";
    image.src="./img/img_products/"+img;
    image.alt=img;

    lien.appendChild(image);
    produit.appendChild(lien);

    var infos = document.createElement("div");
    infos.className="popproduct_info ppp_child";
    infos.id="qt"+code_prod;

    var h4 = document.createElement("h4");
    h4.innerHTML=Titre;

    infos.appendChild(h4);
    infos.innerHTML+=prix+"€ x "+Nqt;
    produit.appendChild(infos);

    button=document.createElement("a");
    button.href="./panier.php";
    button.className="pointer but_link";
    button.id="but_panier";
    button.innerHTML="Voir votre panier";
    content.appendChild(button);

    animeQt_chg(infos, "green");
}
function setup_empty_pan(){ // setup le panier lorsqu'il est vide après avoir retirer un élément
    var content=document.getElementById("ppp_content");
    var button=document.getElementById("but_panier");
    content.removeChild(button);
    content.style.justifyContent="center";

    var image=document.createElement("img");
    image.id="logoPPP";
    image.src="./img/Panier.png";
    image.alt="Panier";

    var txt=document.createElement("p");
    txt.id="vide";
    txt.innerHTML="Votre panier est vide";

    button=document.createElement("a");
    button.href="./panier.php";
    button.className="pointer but_link";
    button.id="but_panier";
    button.innerHTML="Voir votre panier";

    content.appendChild(image);
    content.appendChild(txt);
    content.appendChild(button);
}

function arrondi(valeur, nbArrondi){
    return Math.round(valeur * 10**nbArrondi)/10**nbArrondi;
}

function modif_qt_price(code_prod, Nqt, Price, sign) { //fonction qui modifie la quantité et le prix afficher dans le panier
    var priceTot = document.getElementById("priceTot").innerHTML;
    if (sign == "plus") {
        var prixtot = Number(priceTot) + Number(Price);
        prixtot = arrondi(prixtot, 2);
        document.getElementById("priceTot").innerHTML = prixtot;
    } else if (sign == "minus") {
        var prixtot= Number(priceTot) - Number(Price)
        prixtot = arrondi(prixtot, 2);
        document.getElementById("priceTot").innerHTML = prixtot;
    } else {
        return 0;
    }
    var qtP = document.getElementsByName("txt" + code_prod);
    if (qtP.length < 1 || qtP.length > 1) {
        return 0;
    } else {
        Price = Number(Price) * Number(Nqt);
        Price = arrondi(Price, 2);
        qtP[0].innerHTML = "Prix : " + Price + "€ <br> Quantité : " + Nqt;
    }
}

function sup_in_pan(code_prod){ //supprime un produit en temps réel dans le panier et si le panier est alors vide, setup le panier vide
    var div =document.getElementsByName("div"+code_prod);
    var panier=document.getElementById("panier");
    if(div.length<1 || div.length>1){
        return 0;
   }else{
        panier.removeChild(div[0]);
   }
   var count = document.getElementById("panier").childElementCount;
   if(count==2){
        var panfooter=document.getElementById("pan_footer");
        panier.removeChild(panfooter);

        var empty = document.createElement("div");
        empty.id="pan_footer";
        panier.appendChild(empty);

        var img=document.createElement("img");
        img.id="logoPV";
        img.src="./img/Panier.png";
        img.alt="Panier";
        empty.appendChild(img);

        var p=document.createElement("p");
        p.innerHTML="Votre panier est vide";
        empty.appendChild(p);

        var a=document.createElement("a");
        a.href="./categorie.php?cat=all";
        a.className="pointer but_link";
        a.innerHTML="Voir tous nos produits";
        empty.appendChild(a);

   }
}
function leave_popup() { // Fait apparaitre une popup au dessus des autres éléments
    var pop_add = document.getElementById("valid_achat");
    var main = document.getElementsByTagName('main');
    var maininputs = main[0].getElementsByTagName('input');
    for (let i = 0; i < maininputs.length; i++) {
        maininputs[i].disabled = !maininputs[i].disabled;
    }
    if (pop_add.style.display == "flex") {
        pop_add.style.display = "none";
        document.getElementsByTagName("main")[0].style.filter = "brightness(100%)";
        if (document.getElementById("popup_panier").style.display == "flex") {
            PanierAppear();
        }
    }
}

function sup_intot_price(codeProd, Price) { //fonction qui ajuste le prix total affiché si l'on retire un élément du panier
    var priceTot = document.getElementById("priceTot").innerHTML;
    var qttorem = document.getElementById("commande" + codeProd).innerHTML;
    var Ntot = Number(priceTot) - Price * Number(qttorem);
    Ntot = arrondi(Ntot, 2);
    if (Ntot > 0) {
        document.getElementById("priceTot").innerHTML = Ntot;
    }
}