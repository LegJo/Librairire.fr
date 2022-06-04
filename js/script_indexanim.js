// animation du texte arrondie 

var durAnimLetters = 60;
var bool = 0;


function AnimLetter1(i)
{
    if(i > 25)
    {
        AnimLetter1(i-1);
        bool = 1
    }
    if(i<0)
    {
        var butlogo = document.getElementById("logo_ind");
        setTimeout(()=>{
            if(bool)
                butlogo.onclick= function onclick(event){ WelcomAnimation(); };  
        },durAnimLetters*20);
    }
    else
    {
        var idspan="w" + i;
        var letter = document.getElementById(idspan);
        letter.animate(
            [
                {
                    filter: "drop-shadow(0 0 2px var(--BG_1))", 
                },
                {
                    filter: "drop-shadow(0 0 10px var(--BG_1))",
                },
            ],{
                duration:durAnimLetters,
                iterations: 1,
            }
        );
        setTimeout(()=>{
            if(bool)
                AnimLetter1(i-1);
            else
                AnimLetter1(i+1);
            AnimLetter2(i);
        },durAnimLetters);
    }
}

function AnimLetter2(i)
{
    var idspan="w" + i;
    var letter = document.getElementById(idspan);
    letter.animate(
        [
            {
                filter: "drop-shadow(0 0 10px var(--BG_1))",
            },
            {
                filter: "drop-shadow(0 0 2px var(--BG_1))", 
            },
        ],{
            duration:durAnimLetters+600,
            iterations: 1,
        }
    );
}




function WelcomAnimation()
{    
    var butlogo = document.getElementById("logo_ind");
    if(butlogo.onclick != "")
    {
        butlogo.onclick="";
        bool = 0;
        var i = 0;
        AnimLetter1(i);
    }
}

/**/

function SlideShowProd(){ /*animation du bouton voir tout les produits */
    var divImgProd = document.getElementById("imgProds");
    var imgsProds = document.querySelectorAll("#imgProds img");
    $delaySlid = 50000;
    $nbimg = imgsProds.length;
    $depl = ($nbimg * 200) - 800;
    $depl2 = $depl + 580;
    if($nbimg > 0 )
    {
        divImgProd.animate({ /*deplacement dans un sens*/
                transform: "translateX(-" + $depl +"px)",
            },{
                duration:$delaySlid,
                fill:"forwards",
                iterations: 1,
            }
        );

        setTimeout(()=>{    /*retour dans l'autre sens*/ 
            divImgProd.animate({
                    transform: "translateX(0px)", 
                },{
                    duration:$delaySlid,
                    fill:"forwards",
                    iterations: 1,
                }
            ); 
        },$delaySlid + $delaySlid*0.01); 

        setTimeout(()=>{ /*rebouclage*/
           SlideShowProd();
        }, 2*($delaySlid + $delaySlid*0.01) );  

    }
   
}
