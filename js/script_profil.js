var saveProfileKeys = ["name", "firstName", "sex", "birthDate", "address", "postalCode", "city", "phoneNumber", "job", "mail", "confirmationMail"];
var formSaved = {};

function saveInfo()
{
    for(const nameInfo of saveProfileKeys)
    {
        formSaved[nameInfo] = document.forms["form-profile"][nameInfo].value;
    }

    console.log(formSaved);
}

window.addEventListener('load', saveInfo);

var changeBtn = document.getElementById('changeBtn');
var returnBtn = document.getElementById('returnBtn');
var submitProfile = document.getElementById('submitProfile');

var inputsElement = document.getElementsByClassName('input-profile');
var confirmationMailElement = document.getElementById('divConfirmationMail');


// fontionqqui se lance lorsque l'utilisateur arrte les modifications
function changeOff()
{
    for(const nameInfo of saveProfileKeys)
    {
        document.forms["form-profile"][nameInfo].value = formSaved[nameInfo];
        document.getElementById("errorMessage-profile-"+nameInfo).textContent = "";
    }

    changeBtn.style.display = "flex";
    returnBtn.style.display = "none";
    submitProfile.style.display = "none";

    for(let i = 0; i < inputsElement.length; i++){
        if(!inputsElement[i].checked){
            inputsElement[i].disabled = !inputsElement[i].disabled;
        }
    }

    confirmationMailElement.style.display = "none";

    changeBtn.addEventListener('click', changeOn);
    returnBtn.removeEventListener('click', changeOff);
}

// fonction qui se lance lorsque l'utilisateur souhaite modifier son profile
function changeOn()
{
    returnBtn.style.display = "flex";
    submitProfile.style.display = "block";
    changeBtn.style.display = "none";

    for(let i = 0; i < inputsElement.length; i++){
        if(!inputsElement[i].checked){
            inputsElement[i].disabled = !inputsElement[i].disabled;
        }
    }

    confirmationMailElement.style.display = "flex";

    returnBtn.addEventListener('click', changeOff);
    changeBtn.removeEventListener('click', changeOn);

    
}

changeBtn.addEventListener('click', changeOn);

// function de la pop up de suppression
function pop_cccAppear() {
    console.log("testetstettgeuiivfezr");
    var popup = document.getElementById('pop_delProfile');
    var main = document.getElementsByTagName("main")[0];
    if (popup.style.display != "flex") {
        popup.style.display = "flex";
        main.style.filter = "brightness(30%)";
        let inputToDisabled = main.getElementsByClassName('but_link');
        for(let i = 0; i < inputToDisabled.length; i++){
            inputToDisabled[i].className = "pointer but_link disabled";
        }
    } else {
        popup.style.display = "none";
        main.style.filter = "brightness(100%)";
        let inputDisabled = main.getElementsByClassName('but_link');
        for(let i = 0; i < inputDisabled.length; i++){
            inputDisabled[i].className = "pointer but_link";
        }
    }
}