/*variable globale*/
var messageMail = document.getElementById('messageMail');
var selectProduits = document.getElementById('selectProduits');
var optionProduits = document.getElementsByClassName('allProduits');
var selectedProduits = [];
var display_selected_produits = document.getElementById('display_selected_produits');
/*variable globale*/

/*fonction utile*/

function display_none_produits(){
    var cacher = document.getElementsByClassName("produits");
    for(i=0; i<cacher.length; i++) {
        cacher[i].style.display = "none";
    }
}

function display_select_produits(code){
    var produits = document.getElementsByClassName(code);
    for(i=0; i<produits.length; i++) {
        produits[i].style.display = "block";
    }

}

function Affiche_produits(code){
    display_none_produits();
    display_select_produits(code);
}

//affiche les produits sélectioonés et les renvois pour les passer à l'ajax
function display_list_produit_selected(){
    let listProduitsTitre = "";
    let listProduitsCode = "";
    let selectedOptions = selectProduits.selectedOptions;
    for(let i = 0; i < selectedOptions.length; i++){
        if(selectedOptions[i].textContent !== "--Sélectionner un produit--"){
            if(i+1 == selectedOptions.length)
            {
                listProduitsTitre = listProduitsTitre + selectedOptions[i].textContent;
                listProduitsCode = listProduitsCode + selectedOptions[i].value;
            }
            else{
                listProduitsTitre = listProduitsTitre + selectedOptions[i].textContent + ", ";
                listProduitsCode = listProduitsCode + selectedOptions[i].value + ",";
            }
        }
    }
    display_selected_produits.textContent = listProduitsTitre;
    return listProduitsCode;
}

/*récupére les produits selectionnés et le message pour actualiser l'aperçu avec ajax quand il y a des changements
 dans la zone message et le select des produits */
function updateDemo()
{
    let ajaxListPdtCode = display_list_produit_selected();
    let ajaxMessage = messageMail.value;
    ajaxUpdateDemo(ajaxMessage, ajaxListPdtCode);
}

selectProduits.addEventListener('change', updateDemo);
messageMail.addEventListener('change', updateDemo);


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

//update l'aperçu du mail avec ajax
function ajaxUpdateDemo(message, listPdt)
{
    let request = getXhr();
    request.open('POST', './php/mailSendingProcess.php', true);
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            document.getElementById('display_demo').innerHTML = request.responseText;
        }
    }
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
    console.log('fct=mailSend' + '&message=' + message + '&pdt=' + listPdt)
    request.send('fct=mailSend' + '&message=' + message + '&pdt=' + listPdt);
    return 0;
}
