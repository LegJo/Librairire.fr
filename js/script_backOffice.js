/*variable globale*/
var form_details = document.getElementById("form_details");
var form_prod = document.getElementById("form_prod");
var form_cat = document.getElementById("form_cat");
var infos = document.getElementById("modificateur");
var rubrique_infos = document.getElementById("rubrique_infos");
/**/

/*usefull fonctions*/

function enableBut(compl) //compl = soit "cat" soit "prod".
{
    var butAdd= document.getElementById("but_add_"+ compl);
    var butRem= document.getElementById("but_rem_"+ compl);
    if(compl == "cat" || compl == "prod")
    {
       butAdd.removeAttribute("disabled");
       butRem.removeAttribute("disabled");
       butAdd.classList.remove("disabled");
       butRem.classList.remove("disabled");
       butAdd.style.border="solid 2px rgb(47,47,47)";
       butRem.style.border="solid 2px rgb(47,47,47)";
    }
}

function disableBut(compl) //compl = soit "cat" soit "prod".
{
    var butAdd= document.getElementById("but_add_"+ compl);
    var butRem= document.getElementById("but_rem_"+ compl);
    if(compl == "cat" || compl == "prod")
    {
       butAdd.setAttribute("disabled", "disabled");
       butRem.setAttribute("disabled", "disabled");
       butAdd.classList.add("disabled");
       butRem.classList.add("disabled");
       butAdd.style.border="dashed 2px rgb(47,47,47)";
       butRem.style.border="dashed 2px rgb(47,47,47)";
    }
}

function display_butSend() //Affichage du bouton d'envoi
{
    document.getElementById("but_sendObj").style.display="flex";
}

function display_none_butSend() //Suppresion du bouton d'envoi
{
    document.getElementById("but_sendObj").style.display="none";
}

function display_none_produits(){ //cache tous les produits qui ont été affiché dans la rubrique "produits"
    var cacher = document.getElementsByClassName("produits");
    for(i=0; i<cacher.length; i++) {
        cacher[i].style.display = "none";
    }
}

function display_select_produits(code){ //affiche les produits qui ont le code catégorie (dans leur atribut "class") égale au code catégorie selectionnée
    var produits = document.getElementsByClassName(code);
    for(i=0; i<produits.length; i++) {
        produits[i].style.display = "block";
    }
    document.getElementById("but_add_prod").title = code;   //pour rajouter au bouton "Ajouter un produit" la catégorie selectionnée
    document.getElementById("but_rem_prod").title = code;

}

function display_none_details(){ //cache tous les détails qui ont été affiché dans la rubrique "détails"
    var cacher = document.getElementsByClassName("infos");
    for(i=0; i<cacher.length; i++) {
        cacher[i].style.display = "none";
    }
}

function display_details(produits, cat){ //affiche les infos qui ont le code produit (dans leur atribut "class") égale au code produit sélectionné
    var produits = document.getElementsByClassName(produits);
    for(i=0; i<produits.length; i++) {
        produits[i].style.display = "block";
        produits[i].title = cat.value;
    }
}

function display_form_cat(){ //On affiche le formulaire d'ajout d'une catégorie
    form_cat.style.display = "flex";
    disableBut("prod");
    disableBut("cat");
    display_none_butSend();
}

function display_form_prod(){ //On affiche le formulaire d'ajout d'un produit
    form_prod.style.display = "flex";
    disableBut("prod");
    disableBut("cat");
    display_none_butSend();
}

function display_form_details(){ //On affiche le formulaire d'ajout d'une info
    form_details.style.display = "flex";
    disableBut("cat");
    disableBut("prod");
    display_none_butSend();
}

function display_none_form_cat(){ //On cache le formulaire d'ajout d'une catégorie
    form_cat.style.display = "none";
    enableBut("prod");
    enableBut("cat");
    display_butSend();
}


function display_none_form_prod(){ //On cache le formulaire d'ajout d'un produit
    form_prod.style.display = "none";
    enableBut("prod");
    enableBut("cat");
    display_butSend();
}

function display_none_form_details(){ //On cache le formulaire d'ajout d'un détail
    form_details.style.display = "none";
    enableBut("cat");
    enableBut("prod");
    display_butSend();
}
/**/

/*Fonction appelé*/

function Form_add_cat(){ //va appeler toutes les fonctions nécessaires au bonne affichage du formulaire d'ajout de catégorie
    display_none_form_prod();
    display_none_form_details();
    display_none_produits();
    display_none_details();
    display_form_cat();
}

function Form_add_prod(cat){ //va appeler toutes les fonctions nécessaires au bonne affichage du formulaire d'ajout de produit
    if(document.getElementById("but_add_prod").title == ""){
        var select=document.querySelector("#categories select");
        select.style.border="solid 2px red"; 
        setTimeout(()=>{
            select.style.border="solid 1px rgb(47,47,47)";
        },1000);
    }
    else{
        display_none_form_cat();
        display_none_form_details();
        display_none_details();
        display_select_produits(cat);
        display_form_prod();
        document.getElementById("code_categorie").value = cat;
        //rajouter dans un input hidden la variable cat equivalent au code cat dans lequel crée le produit
    }
    
}

function Affiche_produits(code){ //affiche les produits d'une categorie selectionné 
    display_none_produits();
    display_select_produits(code);
    display_none_details();
    display_none_form_cat();
    display_none_form_prod();
    display_none_form_details();
    document.getElementById("but_rem_cat").title = code;
}

function Affiche_details(produits, cat){ //affiche les details d'un produit selectionné
    display_none_details();
    display_details(produits, cat);
    display_none_form_cat();
    display_none_form_prod();
    display_none_form_details();
    var titre = cat.value+";"+produits;
    document.getElementById("but_rem_prod").title = titre;
}

function Form_details(value, rubrique, code_produit, code_cat){ //affiche le formulaire contenant l'information selectionnée pouvant etre modifié
    display_none_form_cat();
    display_none_form_prod();
    display_form_details();
    infos.value = value;
    rubrique_infos.value = rubrique;
    if(rubrique == "code_cat"){ //on ne veut pas que l'admin puisse chnager le code categorie, ou alors si on à le temps on fera une fonction expres qui deplace l'objet produit dans une autre categories
        infos.setAttribute("disabled", "disabled");
        document.getElementById("envoi3").style.display = "none";
    }
    else{
        infos.removeAttribute("disabled");
        document.getElementById("envoi3").style.display = "block";
    }
    document.getElementById("code_produit").value = code_produit.value; //car code_produit est un objetHTML
    document.getElementById("code_cati").value = code_cat;
}

/*Fonction appelé*/

var cat;
function initCat(join_ccat,join_ncat, pefc, allProd_infos) //initialisation d'un objet à 3 dimensions contenant toutes les infos des produits en fonction des categories etc.. pour une gestion facile dans une variable globale
{
    let catoz=new Object();
    var tab_ccat = join_ccat.split(';');
    var tab_ncat = join_ncat.split(';');
    var tmp = [];
    var tmp2 = [];
    for(var i=0; i<tab_ccat.length ; i++)
    {
        catoz[tab_ccat[i]]=new Object();
        catoz[tab_ccat[i]]['cat_name']= tab_ncat[i];
        tmp=pefc.split('/')[i];
        var code_prod=tmp.split(';');
        tmp2=allProd_infos.split('|');
        var tmp3= tmp2[i].split('/');
        for(var j=0; j<tmp3.length; j++)
        {
            if(code_prod[j] != undefined)
            {
                var tmp4=tmp3[j].split(';');
                catoz[tab_ccat[i]][code_prod[j]] = new Object() ;
                catoz[tab_ccat[i]][code_prod[j]]["Titre"] = tmp4[0];
                catoz[tab_ccat[i]][code_prod[j]]["description"] = tmp4[1];
                catoz[tab_ccat[i]][code_prod[j]]["code_cat"] = tmp4[2];
                catoz[tab_ccat[i]][code_prod[j]]["auteur"] = tmp4[3];
                catoz[tab_ccat[i]][code_prod[j]]["synopsis"] = tmp4[4];
                catoz[tab_ccat[i]][code_prod[j]]["langue"] = tmp4[5];
                catoz[tab_ccat[i]][code_prod[j]]["ISBN-10"] = tmp4[6];
                catoz[tab_ccat[i]][code_prod[j]]["editeur"] = tmp4[7];
                catoz[tab_ccat[i]][code_prod[j]]["pic_name"] = tmp4[8];
                catoz[tab_ccat[i]][code_prod[j]]["quantite"] = tmp4[9];
                catoz[tab_ccat[i]][code_prod[j]]["prix"] = tmp4[10];
            }
        }
    }
    cat = catoz ;//cette ligne ridicule est importante 
    //pour comprendre l'objet qu'on créé ici: faire un console.log(cat)
}

function test() //Affiche l'objet
{
    console.log(cat);
}


function modifierInfos(){   //modifie dans l'objet l'info modifié d'un produit
    var modification = document.getElementById("modificateur").value;
    var rubrique_infos = document.getElementById("rubrique_infos").value;
    var code_produit = document.getElementById("code_produit").value;
    var code_categorie = document.getElementById("code_cati").value;
    //console.log(rubrique_infos+";"+code_produit);
    var selectedInfo=document.querySelector("#" + rubrique_infos + "." + code_produit);
    if(modification != ""){ //dans ce if on peut ajouter d'autre sécurité si on veut
        cat[code_categorie][code_produit][rubrique_infos] = modification;
        document.getElementById("modificateur").style.border="initial";
        //test();
        display_none_form_details();
    }
    else{
        document.getElementById("modificateur").style.border="solid 2px red";
        document.getElementById("modificateur").setAttribute("placeholder", "Impossible d'enregistrer une information vide");
    }    
    selectedInfo.style.transition ="1s"
    selectedInfo.style.backgroundColor = "green";
    selectedInfo.style.color="var(--BG_3)";
    setTimeout(()=>{
        selectedInfo.style.backgroundColor = "";
        selectedInfo.style.color="black";
    },1000);
    selectedInfo.value = modification; //modifie le select qui a pour id la rubrique info
}

function addCatInDOM(titre_cat, titre_prod, code_prod, code_cat){ //Ecrit les infos de la catégorie créé dans le DOM
    var selectcat = document.querySelector("#categories select");
    var newoptioncat = document.createElement("option");
    newoptioncat.setAttribute("id" , code_cat);
    newoptioncat.setAttribute("value" , code_cat);
    newoptioncat.setAttribute("onclick" , "Affiche_produits(value)");
    newoptioncat.innerHTML=titre_cat;
    selectcat.appendChild(newoptioncat);
    addProdInDOM(titre_prod, code_prod, code_cat);
}

function addProdInDOM(titre ,code_prod, code_cat) //Ecrit les infos du produit créé dans le DOM
{
    var selectprod = document.querySelector("#produits select");
    var newoptionprod = document.createElement("option");
    newoptionprod.setAttribute("style" , "display: block;");
    newoptionprod.setAttribute("class" , code_cat + " produits");
    newoptionprod.setAttribute("id" , code_prod);
    newoptionprod.setAttribute("value" , code_prod);
    newoptionprod.setAttribute("onclick" , "Affiche_details(value," + code_cat + ")");
    newoptionprod.innerHTML=titre;
    selectprod.appendChild(newoptionprod);
    var tabinfo=["Titre", "description", "code_cat", "auteur", "synopsis", "langue", "ISBN-10", "editeur", "pic_name", "quantite", "prix"];
    for(i=0; i<tabinfo.length; i++){
        addInfosinDOM(tabinfo[i], code_prod,code_cat);
    }
    display_none_details();
}

function addInfosinDOM(info, code_prod, code_cat){ //Ecrit les infos de l'info produit  modifié dans le DOM
    var selectdet = document.querySelector("#details select");
    var newoptioninfos = document.createElement("option");
    newoptioninfos.setAttribute("style" , "display: block;");
    newoptioninfos.setAttribute("class" , code_prod + " infos");
    newoptioninfos.setAttribute("id" , info);
    newoptioninfos.setAttribute("title" , code_cat);
    newoptioninfos.setAttribute("value" , cat[code_cat][code_prod][info]);
    newoptioninfos.setAttribute("onclick" , "Form_details(value, id, " + code_prod+ ", title)");
    newoptioninfos.innerHTML=info;
    selectdet.appendChild(newoptioninfos);
}

function addProduit(){ //ajoute un produit dans l'objet JS
    var titre_product = document.getElementById("titre_input2"); //utiliser le titre pour donner le nom du select à ecrire dans le HTML
    var description_product = document.getElementById("description_input2");
    var auteur_product = document.getElementById("auteur_input2");
    var synopsis_product = document.getElementById("synopsis_input2");
    var langue_product = document.getElementById("langue_input2");
    var isbn_product = document.getElementById("ISBN_input2");
    var editeur_product = document.getElementById("editeur_input2");
    var pic_name_product = document.getElementById("pic_name_input2");
    var quantite_product = document.getElementById("quantite_input2");
    var prix_product = document.getElementById("prix_input2");
    var code_categorie = document.getElementById("code_categorie").value;
    if( titre_product.value=="" || description_product.value=="" || auteur_product.value=="" || synopsis_product.value=="" || langue_product.value=="" || isbn_product.value=="" || editeur_product.value=="" || pic_name_product.value=="" || quantite_product.value=="" || prix_product.value==""){
         document.getElementById("div_form_prod").style.border="solid 2px red";
    }
    else
    { 
        document.getElementById("div_form_prod").style.border="initial";
        /* !!on cherche le dernier code produit toute categorie confondue pour ne pas creer de doublon dans les codes produit entre différentes categories!! */
        var last_prod;
        for(var code_cat in cat){ //!!on cherche le dernier code produit toute categorie confondue pour ne pas creer de doublon dans les codes produit entre différentes categories!!
            for(var code_prod in cat[code_cat]){
                last_prod=code_prod;
            }
        }
        var num_last_prod = last_prod.replace(/^./, "");
        var num_code_prod = parseInt(num_last_prod);
        num_code_prod++;
        var new_code_prod="PDefault";
        if(num_code_prod <= 9)
        {
            new_code_prod = "P0"+num_code_prod;
        }
        else{
            new_code_prod = "P"+num_code_prod;
        }
          
        cat[code_categorie][new_code_prod] = new Object(); //il est important de garder la meme forme pour chaque objet produit 
        cat[code_categorie][new_code_prod]["Titre"] = titre_product.value;
        cat[code_categorie][new_code_prod]["description"] = description_product.value;
        cat[code_categorie][new_code_prod]["code_cat"] = code_categorie; 
        cat[code_categorie][new_code_prod]["auteur"] = auteur_product.value;
        cat[code_categorie][new_code_prod]["synopsis"] = synopsis_product.value;
        cat[code_categorie][new_code_prod]["langue"] = langue_product.value;
        cat[code_categorie][new_code_prod]["ISBN-10"] = isbn_product.value;
        cat[code_categorie][new_code_prod]["editeur"] = editeur_product.value;
        cat[code_categorie][new_code_prod]["pic_name"] = pic_name_product.value;
        cat[code_categorie][new_code_prod]["quantite"] = quantite_product.value;
        cat[code_categorie][new_code_prod]["prix"] = prix_product.value;
        //test();
        addProdInDOM(titre_product.value, new_code_prod, code_categorie); //ajout du prod dasn le dom
        //on remet le form à 0 pour que l'utilisateur puisse recréer des produit
        titre_product.value="";
        description_product.value="";
        auteur_product.value="";
        synopsis_product.value="";
        langue_product.value="";
        isbn_product.value="";
        editeur_product.value="";
        pic_name_product.value="";
        quantite_product.value="";
        prix_product.value="";
        display_none_form_prod();
    }
}

function remProd(titre){ //retire le produit (sélectionné dans le DOM) dans l'objet JS
    if(titre.length < 5) {//car si l'element est selectionné alors son attribut titre est composé de "cXX"+"PXX" donc la taille de titre = 6
        var select=document.querySelector("#produits select");
        select.style.border="solid 2px red"; 
        setTimeout(()=>{
            select.style.border="solid 1px rgb(47,47,47)";
        },1000);
    }
    else 
    {
        var codeCat_codeProd = titre.split(";"); //codeCat_codeProd[0] donne le code categorie codeCat_codeProd[1] donne le code produit
        delete cat[codeCat_codeProd[0]][codeCat_codeProd[1]]; //on supprime l'objet produit
        var select_codeprod=document.getElementById(codeCat_codeProd[1]);
        display_none_details(); //on retire du DOM les details du produit supprimer
        select_codeprod.remove(); //on recupere l'option du select correspondant au code et on le supprime
        var catlen = Object.keys(cat[codeCat_codeProd[0]]).length;
        if (catlen == 1){
        remCat(codeCat_codeProd[0]);
        }
        test();
    }
}

function remCat(code_cat){ //remove une categorie de l'objet cat et du DOM
    if(code_cat == "")
    {
        var select=document.querySelector("#categories select");
        select.style.border="solid 2px red"; 
        setTimeout(()=>{
            select.style.border="solid 1px rgb(47,47,47)";
        },1000);
    }
    else {
        delete cat[code_cat];
        var select_codecat=document.getElementById(code_cat);
        display_none_produits(); //on retire du DOM les produit de la categories
        select_codecat.remove(); //on recupere l'option du select correspondant au code et on le supprime 
        //test();
    }
}

function addCategorie(){ //ajoute une catégorie dans l'objet JS
    var cat_name = document.getElementById("name_cat_input1"); //pour donner le nom de la categorie dans le select du dom
    var titre_product = document.getElementById("titre_input1"); //utiliser le titre pour donner le nom du select à ecrire dans le HTML
    var description_product = document.getElementById("description_input1");
    var auteur_product = document.getElementById("auteur_input1");
    var synopsis_product = document.getElementById("synopsis_input1");
    var langue_product = document.getElementById("langue_input1");
    var isbn_product = document.getElementById("ISBN_input1");
    var editeur_product = document.getElementById("editeur_input1");
    var pic_name_product = document.getElementById("pic_name_input1");
    var quantite_product = document.getElementById("quantite_input1");
    var prix_product = document.getElementById("prix_input1");
    if(cat_name.value=="" || titre_product.value=="" || description_product.value=="" || auteur_product.value=="" || synopsis_product.value=="" || langue_product.value=="" || isbn_product.value=="" || editeur_product.value=="" || pic_name_product.value=="" || quantite_product.value=="" || prix_product.value==""){
        document.getElementById("div_form_cat").style.border="solid 2px red";
    }
    else
    { 
        document.getElementById("div_form_cat").style.border="initial";
        /* !!on cherche le dernier code produit toute categorie confondue pour ne pas creer de doublon dans les codes produit entre différentes categories!! */
        var last_prod;
        for(var code_cat in cat){ 
            for(var code_prod in cat[code_cat]){
                last_prod=code_prod;
            }
        }
        var num_last_prod = last_prod.replace(/^./, "");
        var num_code_prod = parseInt(num_last_prod);
        num_code_prod++;
        var new_code_prod = "P"+num_code_prod;

        /* on cherche maintenant un code catégorie disponible */
        var last_cat;
        for(var code_cat in cat){
            last_cat=code_cat;
        }
        var num_last_cat = last_cat.replace(/^./, "");
        var num_code_cat = parseInt(num_last_cat);
        num_code_cat++;
        var new_code_cat="cDefault";
        if(parseInt(num_code_cat) <= 9)
             new_code_cat = "c0"+num_code_cat;
        else
        {
            new_code_cat = "c"+num_code_cat;
        }

        cat[new_code_cat] = new Object();
        cat[new_code_cat]["cat_name"] = cat_name.value;
        cat[new_code_cat][new_code_prod] = new Object(); //on garde le même format pour tout les objet produit 
        cat[new_code_cat][new_code_prod]["Titre"] = titre_product.value;
        cat[new_code_cat][new_code_prod]["description"] = description_product.value;
        cat[new_code_cat][new_code_prod]["code_cat"] = new_code_cat; 
        cat[new_code_cat][new_code_prod]["auteur"] = auteur_product.value;
        cat[new_code_cat][new_code_prod]["synopsis"] = synopsis_product.value;
        cat[new_code_cat][new_code_prod]["langue"] = langue_product.value;
        cat[new_code_cat][new_code_prod]["ISBN-10"] = isbn_product.value;
        cat[new_code_cat][new_code_prod]["editeur"] = editeur_product.value;
        cat[new_code_cat][new_code_prod]["pic_name"] = pic_name_product.value;
        cat[new_code_cat][new_code_prod]["quantite"] = quantite_product.value;
        cat[new_code_cat][new_code_prod]["prix"] = prix_product.value;
        //test();
        addCatInDOM(cat_name.value, titre_product.value, new_code_prod, new_code_cat); //ajout de la cat dans le dom
        //on remet les champs du form à 0 pour que l'utilisateur puisse créer de nouvelle catégorie
        cat_name.value="";
        titre_product.value="";
        description_product.value="";
        auteur_product.value="";
        synopsis_product.value="";
        langue_product.value="";
        isbn_product.value="";
        editeur_product.value="";
        pic_name_product.value="";
        quantite_product.value="";
        prix_product.value="";
        display_none_form_cat();
    }
}

function SendObjToServ()//fonction qui va envoyer l'obj à 3 dimension qui contient toutes les infos au serveur pour qu'elles soit réécrite
{
    var ObjFinal = JSON.stringify(cat);
    //console.log(ObjFinal);
    document.getElementById("obj_final").value=ObjFinal;
    document.forms["BO_changes"].submit();
}