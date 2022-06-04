function Valeur(value, quantite, codepdt) { // modifie la valeur que le consommateur souhaite commader allant de 1 au stock max
    var achat = document.getElementById("commande" + codepdt).innerHTML;
    if (value == "+") {
        if (achat < quantite) {
            document.getElementById("commande" + codepdt).innerHTML = Number(achat) + 1;
        } else {
            return 0;
        }
    } else if (value == "-") {
        if (achat > 1) {
            document.getElementById("commande" + codepdt).innerHTML = Number(achat) - 1;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
function stock_aff(quantite) { // Permet d'afficher un texte de différentes couleurs indiquant à tous les utilisateurs s'il reste plus ou moins de stock
    var before = document.head.appendChild(document.createElement("style"));
    var stock = document.getElementById("stock");
    if (quantite > 10) {
        document.getElementById("stock").innerHTML = " Il reste du stock.";
        stock.style.color = 'green';
        before.innerHTML = "#stock:before {content: url(./img/dots_stock/vert.png);}";
    } else if (quantite == 0 || quantite == "erreur") {
        document.getElementById("stock").innerHTML = " Il ne reste plus de stock.";
        stock.style.color = 'red';
        before.innerHTML = "#stock:before {content: url(./img/dots_stock/rouge.png);}";
    } else if (quantite > 0 && quantite < 10) {
        document.getElementById("stock").innerHTML = " Il ne reste plus beaucoup de stock.";
        stock.style.color = '#e9540e';
        before.innerHTML = "#stock:before {content: url(./img/dots_stock/orange.png);}";
    }
}

function pop_cccAppear() { // Affiche une popup si l'on essaye de valider le panier sans être connecté
    var popup = document.getElementById('pop_cmdconnect');
    var main = document.getElementsByTagName("main")[0];
    var maininputs = main.getElementsByTagName('input');
    if (document.getElementsByClassName("but_link pointer enabled")[0] != undefined) {// Fait en sorte que les liens en arrière plan ne fonctionnent pas lorsque la popup est affichée
        document.getElementsByClassName("but_link pointer enabled")[0].className = "but_link pointer disabled";
    } else if (document.getElementsByClassName("but_link pointer disabled")[0] != undefined) {
        console.log("test");
        document.getElementsByClassName("but_link pointer disabled")[0].className = "but_link pointer enabled";
    }
    for (let i = 0; i < maininputs.length; i++) {
        maininputs[i].disabled = !maininputs[i].disabled;//Fait en sorte que les boutons en arrière plan ne fonctionnent pas lorsque la popup est affichée
    }
    if (popup.style.display != "flex") {
        popup.style.display = "flex";
        main.style.filter = "brightness(30%)";
    } else {
        popup.style.display = "none";
        main.style.filter = "brightness(100%)";
    }
}