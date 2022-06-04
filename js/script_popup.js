
function PanierAppear()//fonction du clic sur le bouton panier 
{
    var durAnimPop=200;
    var pop_pan = document.getElementById("popup_panier");
    var pop_con = document.getElementById("popup_connect");
    if(pop_pan.style.display == "flex")
    {
        PanConAnim2(pop_pan, durAnimPop);
    }
    else
    { 
        if(pop_con.style.display == "flex")
        {
            PanConAnim2(pop_con, durAnimPop);
            setTimeout(()=>{
                PanConAnim1(pop_pan, durAnimPop);
            },durAnimPop)
        }
        else{
            PanConAnim1(pop_pan, durAnimPop);
        }
    }
}

function ConnectAppear()//fonction du clic sur le bouton Connexion 
{
    var durAnimPop=200;
    var pop_con = document.getElementById("popup_connect");
    var pop_pan = document.getElementById("popup_panier");
    if(pop_con.style.display == "flex")
    {
        PanConAnim2(pop_con, durAnimPop);
    }
    else
    { 
        if(pop_pan.style.display == "flex")
        {
            PanConAnim2(pop_pan, durAnimPop);
            setTimeout(()=>{
                PanConAnim1(pop_con, durAnimPop);
            },durAnimPop)
        }
        else{
            PanConAnim1(pop_con, durAnimPop);
        }
    }
}

function PanConAnim1(pop, durAnimPop) //animation pour ouvrir popup panier & connexion 
{
    pop.style.display="flex";
    pop.animate(
        [
            {
                transform: "scale(0)",
                transformOrigin: "top right",
            },
            {
                transform: "scale(1)",
                transformOrigin: "top right",
            },
        ],{
            duration:durAnimPop,
            fill: "forwards",
            iterations: 1,
        });
}

function PanConAnim2(pop, durAnimPop) //animation pour ferme popup panier & connexion 
{
    pop.animate(
        [
            {
                transform: "scale(1)",
                transformOrigin: "top right",
            },
            {
                transform: "scale(0)",
                transformOrigin: "top right",
            },
        ],{
            duration:durAnimPop,
            fill: "forwards",
            iterations: 1,
        });
    setTimeout(()=>{
        pop.style.display="none";
    },durAnimPop)
}
        


function AsideAppear() {
    var durAnimAs=330;
    var aside = document.querySelector("aside");
    var main_content = document.getElementById("main_content");
    if(aside.style.display == "flex") {
        aside.animate({
                transform: "translateX(0px)"
            },{
                duration:durAnimAs,
                fill: "forwards",
                iterations: 1,
            });
        main_content.animate({
                transform: "translateX(0px)",
                marginRight: "0px",
            },{
                duration:durAnimAs,
                fill: "forwards",
            });  
        setTimeout(() => {
            aside.style.display="none";
        },durAnimAs);
    }
    else {
        aside.style.display="flex";
        aside.animate({
            transform: "translateX(200px)"
            },{
                duration:durAnimAs,
                fill: "forwards",
            });
        main_content.animate({
                transform: "translateX(200px)",
                marginRight: "200px",
            },{
                duration:durAnimAs,
                fill: "forwards",
            });  
    }

}