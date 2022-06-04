
/*
function showErrorMessage(target, nbRowMessage, messages){
    if(nbRowMessage > 0 && typeof messages === "object"){
        let bufferErrorMessage = "";
        for(let i = 0; i < nbRowMessage; i++){
            bufferErrorMessage += "<td>"+messages[i]+"</td>";
        }
        target.innerHTML = bufferErrorMessage;
    }
}
*/

    // fonction verifiant la validiter d'une date qui doit être avant un délai avant aujourd'hui (date de nassance avec un age minimum)
    function testBirthDate(inputDate, marginYears){
    let today = new Date;
    let minAge = '';
    if ((today.getMonth()+1) > 9){
        minAge = (today.getFullYear()-marginYears) + '-' + (today.getMonth()+1) + '-' + today.getDate() ;
    }
    else{
        minAge = (today.getFullYear()-marginYears) + '-0' + (today.getMonth()+1) + '-' + today.getDate() ;
    }

    if(inputDate == ""){
        return false;
    }
    else if(Date.parse(inputDate) > Date.parse(today)){
        return false;
    }
    else if(Date.parse(inputDate) > Date.parse(minAge)){
        return false;
    }
    else{
        return true;
    }
}

// function verifiant la validiter d'une date comprise entre aujourd'hui et une date superieur (date de contact)
function testContactDate(inputDate, marginYears){
    let today = new Date;
    

    let todayString = '';
    
    /*today = today.setSeconds = 0;
    today = today.setMinutes = 0;
    today = today.setHours = 0;
    */
    console.log(today);
    let minAge = '';
    if ((today.getMonth()+1) > 9){
        minAge = (today.getFullYear()+marginYears) + '-' + (today.getMonth()+1) + '-' + today.getDate() ;
        todayString = (today.getFullYear()) + '-' + (today.getMonth()+1) + '-' + today.getDate() ;
    }
    else{
        minAge = (today.getFullYear()+marginYears) + '-0' + (today.getMonth()+1) + '-' + today.getDate() ;
        todayString = (today.getFullYear()) + '-0' + (today.getMonth()+1) + '-' + today.getDate() ;
    }

    if(inputDate == ""){
        return false;
    }
    else if(Date.parse(inputDate) < Date.parse(todayString)){
        return false;
    }
    else if(Date.parse(inputDate) > Date.parse(minAge)){
        return false;
    }
    else{
        console.log("c bon");
        return true;
    }
}



//-------------------------------

// fonction permettant de mettre la première lettre d'un mot en majuscule
function ucwords(str)
{
    strUC = str.toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(letter) {
        return letter.toUpperCase();
    });
    return strUC;
}


// fonction pour verifier la validiter des boutons radio pour le choix du sexe
function checkSexRadio(sexElement)
{
    let nbCheck = 0;
    for (let i = 0; i < sexElement.length; i++) {
        if (sexElement[i].checked && (sexElement[i].value === "Man" || sexElement[i].value === "Woman" || sexElement[i].value === "Other")) 
        {
           nbCheck++;
        }
    }
    if(nbCheck === 1){return true;}else{return false;}
}


// fonction verifiant la validité d'une entré de formulaire selon un pattern
function checkWithPattern(input, pattern, dataName, allowedCharacter, errorMessageElement)
{
    data = input.value.trim();
    let usedPattern = new RegExp(pattern);
    if(!(usedPattern.test(data)))
    {
        errorMessageElement.innerHTML = ucwords(dataName)+" incorrect. Les caracteres autorisés sont "+allowedCharacter+".";
        input.className += "invalidInput";
        return 1;
    }
    else
    {        
        return 0;
    }
}


/*
    Fonction de verification de formulaire qui fonctionne pour tous les formulaires
*/
function checkingForm(formName)
{
    if(formName === "registration" || formName === "profile" || formName === "contact" || formName === "validpan")
    {
        //console.log("test");
        let keySet = formName+"Keys";

        // définition des nom des entrées possible pour chaque formulaire
        let registrationKeys = ["name", "firstName", "sex", "birthDate", "mail", "confirmationMail", "password", 
            "confirmationPassword", "address", "postalCode", "city", "phoneNumber", "job"];
        let profileKeys = ["name", "firstName", "sex", "birthDate", "address", "postalCode", "city", "phoneNumber", "job", "mail", "confirmationMail"];
        let contactKeys = ["topic", "name", "firstName", "sex", "mail", "contactDate", "message"];
        let validpanKeys = ["name", "firstName", "postalCode", "city", "address"];

        var accentedCharacters = "àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ";

        // définition des tableaux des champs pouvant être verifier avec une expression regulière
        let checkWithPatternKeys = ["name", "firstName", "mail", "password", "address", "postalCode", "city", "phoneNumber", "job", "message"];
        let patterns = {"name": "^[a-zA-Z"+accentedCharacters+"' \\-]{1,32}$", "firstName": "^[a-zA-Z"+accentedCharacters+"' \\-]{1,32}$",
            "mail" : "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\\.[a-zA-Z0-9-]+)*$", 
            "password": "^(?=.*\\d)(?=.*[A-Z])(?=.*[-!@#$%_])(?=.*[a-z])[0-9A-Za-z!@#$%\-_]{7,30}$", 
            "address": "^([a-zA-Z0-9"+accentedCharacters+"' \\-,\\.]|\\r\\n|\\r|\\n){1,256}$", "postalCode": "^\\d{5}$", 
            "city": "^[a-zA-Z"+accentedCharacters+"' \\-]{1,32}$", "phoneNumber": "^0\\d{9}$", "job": "^[a-zA-Z0-9"+accentedCharacters+"' \\-]{1,128}$",
            "message": "^([a-zA-Z0-9"+accentedCharacters+"' \\-,\\.]|\\r\\n|\\r|\\n){1,512}$"
        };

        // définition des tableaux permettant de génerer une message d'erreur différent selon l'entrée
        let allDataName = {
            "name": "nom", "firstName": "prénom", "sex": "sexe", "birthDate": "date de naissance", "mail": "mail", 
            "confirmationMail": "mail de confirmation",  "password": "mot de passe", "confirmationPassword": "mot de passe de confirmation", 
            "address": "adresse", "postalCode": "code postal", "city": "ville", "phoneNumber": "numéro de téléphone", "job": "metier",
            "topic": "sujet", "contactDate": "date de contact", "message": "message"
        };
        let allAllowedCharacter = {
            "name": "les lettres, -", "firstName": "les lettres, -", "password": "les minuscules, majuscules, chiffres, -!@#$%_, avec un de chaque minimum 7 caracter",
            "address": "les lettres, chiffres, -.,'", "postalCode": "5 chiffes", "city": "les lettres, -'", "phoneNumber": "10 chiffes dont le premier est 0", 
            "job": "les lettres, les chiffres, -'", "message": "les lettres, chiffres, -.,'"
        }

        var errorNumber = 0;

        var formInputs = {};
        for(const nameInput of eval(keySet))
        {
            formInputs[nameInput] = document.forms["form-"+formName][nameInput];
        }

        // boucle sur le tableau concerné par la verification grace à un nom de variable variable (grace à eval)
        for(const nameInput of eval(keySet))
        {
            errorMessageElement = document.getElementById("errorMessage-"+formName+"-"+nameInput);
            errorMessageElement.textContent = "";

            if(nameInput !== "sex" && formInputs[nameInput].value === "") // verification si le champ est vide et génération d'un message d'erreur si c'est la cas
            {
                errorMessageElement.textContent = "Veuillez remplir votre "+allDataName[nameInput]+".";
                errorNumber++;
            }
            else
            {
                if(checkWithPatternKeys.includes(nameInput)) // verification pour tous les champs se verifiant à l'aide d'un pattern
                {
                    errorNumber += checkWithPattern(formInputs[nameInput], patterns[nameInput], allDataName[nameInput], allAllowedCharacter[nameInput], errorMessageElement);
                }
                // verification des autres champs devant être faite individuellement 
                else if(nameInput === "sex")
                {
                    if(!(checkSexRadio(formInputs[nameInput]))) // verification du sexe
                    {
                        errorMessageElement.textContent = "Veuillez indiquer votre sexe.";
                        errorNumber++;
                    }
                    
                }
                else if(nameInput === "birthDate")
                {
                    if(!(testBirthDate(formInputs[nameInput].value, 12)))  // verification de la date de naissance
                    {
                        errorMessageElement.textContent = "Vous n'avez pas correctement indiqué votre date de naissance. \nVous devez avoir au minimum 12 ans.";
                        errorNumber++;
                    }
                }
                else if((formName === "registration" || formName === "profile") && nameInput === "confirmationMail")
                {
                    let usedPattern = new RegExp(patterns["mail"]);
                    if(!usedPattern.test(formInputs[nameInput].value.trim()))  // verification du mail de confirmation avec une expression régulière
                    {
                        errorMessageElement.textContent = "Veuillez indiquer une adresse email valide.";
                        errorNumber++;
                    }
                    else
                    {   
                        if(formInputs["mail"].value.trim() !== formInputs["confirmationMail"].value.trim()) // verification de l'égalité entre le mail et le mail de confirmation
                        {
                            errorMessageElement.textContent = "Les deux adresses email ne correspondent pas.";
                            errorNumber++;
                        }
                    }
                }
                else if(formName === "registration" && nameInput === "confirmationPassword")
                {
                    let usedPattern = new RegExp(patterns["password"]);
                    if(!usedPattern.test(formInputs[nameInput].value.trim()))  // verification du mot de passe de confirmation avec une expression regulière
                    {
                        errorMessageElement.textContent = "Veuillez indiquer un mot de passe valide.";
                        errorNumber++;
                    }
                    else
                    {        
                        if(formInputs["password"].value.trim() !== formInputs["confirmationPassword"].value.trim())  // verification de l'égalité entre le mot de passe et le mot de passe de confirmation
                        {
                            errorMessageElement.textContent = "Les deux mots de passe ne correspondent pas.";
                            errorNumber++;
                        }
                    }
                }
                else if(nameInput === "topic")  //verification du sujet pour le formulaire de contact
                {
                    if(!(formInputs[nameInput].selectedIndex >=0 && (formInputs[nameInput].value  === "Remboursement" || formInputs[nameInput].value === "Livraison" || formInputs[nameInput].value === "Autre")))
                    {
                        errorMessageElement.textContent = "Une erreur est survenu. Veuillez choisir le sujet.";
                        errorNumber++;
                    }
                }
                else if(nameInput === "contactDate") //verification de la date de contact
                {
                    if(!(testContactDate(formInputs[nameInput].value, 1)))
                    {
                        errorMessageElement.textContent = "Vous n'avez pas correctement indiqué votre date de contact. \nLe délai doit être d'un an maximum.";
                        errorNumber++ ;
                    }
                }
                
            }
        }
        if(errorNumber === 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }

}