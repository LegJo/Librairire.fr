<?php
    require_once("./manage_panier.php");
    
    // fonction  permettant de securiser les informations reçu par un formulaire
    function valid_data(string $data){
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // fonction verifiant la validiter d'une date qui doit être avant un délai avant aujourd'hui (date de nassance avec un age minimum)
    function valid_date_inf(string $inputDate, int $minimumYears){
        $date = $inputDate;
        if(strtotime($date))
        {
            if(strpos($date,'-') !== false) 
            {
                list($year, $month, $day) = explode('-', $date);
                //return checkdate($month, $day, $year);
                if(checkdate($month, $day, $year) !== false) 
                {
                    $dateAgeMaxTab = explode('-', date("Y-m-d"));
                    $dateAgeMaxTab[0] -= $minimumYears;
                    $dateAgeMax = implode('-', $dateAgeMaxTab);
                    $dateAgeMin = '1900-01-01';

                    if($date < $dateAgeMax && $date > $dateAgeMin)
                    {
                        return true;
                    }
                    else{
                        return false;
                    }
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
        else
        {
            return false;
        }
    }

    // function verifiant la validiter d'une date comprise entre aujourd'hui et une date superieur (date de contact)
    function valid_date_sup(string $inputDate, int $marginYears){
        $date = $inputDate;
        if(strtotime($date))
        {
            if(strpos($date,'-') !== false) 
            {
                list($year, $month, $day) = explode('-', $date);
                //return checkdate($month, $day, $year);
                if(checkdate($month, $day, $year) !== false) 
                {
                    $dateAgeMaxTab = explode('-', date("Y-m-d"));
                    $dateAgeMaxTab[0] += $marginYears;
                    $dateAgeMax = implode('-', $dateAgeMaxTab);
                    $dateAgeMin = date('Y-m-d');

                    if($date < $dateAgeMax && $date >= $dateAgeMin)
                    {
                        return true;
                    }
                    else{
                        return false;
                    }
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
        else
        {
            return false;
        }
    }


    /* function verifiant la présence de certaines valeur dans le fichier utilisateur, 
    elle utilisé notament lors de l'inscription pour verifier que le mail entré n'est pas déja utilisé */

    function presenceJson(array $keys, array $fields){
        $usersJSON = file_get_contents(ROOT.FILE_USERS_JSON);
        $usersTab = json_decode($usersJSON, true);
        $presence = [];
        foreach($usersTab as $user)
        {   
            foreach($keys as $key)
            {
                if($user[$key] === $fields[$key]){
                    $presence[$key] = 1;
                }
                else{
                    if(empty($presence[$key]))
                    {
                        $presence[$key] = 0;
                    }
                }
            }
        }
        return $presence;
    }


    // fonction verifiant la validité d'une entré de formulaire selon un pattern
    function checkWithPattern(string $nameInput,string $data,string $pattern, string $dataName, string $allowedCharacter, string $errorSessionName, string $correctInputSessionName)
    {
        if(preg_match($pattern, $data) === 0)
        {
            $_SESSION[$errorSessionName][$nameInput] = ucwords($dataName)." incorrect. Les caracteres autorisés sont ".$allowedCharacter.".";
            return 1;
        }
        else
        {
            $_SESSION[$correctInputSessionName][$nameInput] = $data;
            return 0;
        }
    }


    /*
        Fonction de verification de formulaire qui fonctionne pour tous les formulaires
    */
    function checkingForm(string $formName, array $formInputs)
    {
        if(!empty($formName) && !empty($formInputs) && ($formName === "registration" || $formName === "profile" || $formName === "contact" || $formName === "validpan"))   //verification du nom de formualire entré
        {
            $keySet = $formName."Keys";

            // définition des nom des entrées possible pour chaque formulaire
            $registrationKeys = ["name", "firstName", "sex", "birthDate", "mail", "confirmationMail", "password", 
                "confirmationPassword", "address", "postalCode", "city", "phoneNumber", "job"];
            $profileKeys = ["name", "firstName", "sex", "birthDate", "address", "postalCode", "city", "phoneNumber", "job", "mail", "confirmationMail"];
            $contactKeys = ["topic", "name", "firstName", "sex", "mail", "contactDate", "message"];
            $validpanKeys = ["name", "firstName", "postalCode", "city", "address"];

            // définition des tableaux des champs pouvant être verifier avec une expression regulière
            $checkWithPatternKeys = ["name", "firstName", "password", "address", "postalCode", "city", "phoneNumber", "job", "message"];
            $patterns = ["name" => "/^[\p{L}\-]{1,32}$/u", "firstName" => "/^[\p{L}\-]{1,32}$/u", "password" => "/^(?=.*\d)(?=.*[A-Z])(?=.*[-!@#$%_])(?=.*[a-z])[0-9A-Za-z!@#$%_\-]{7,30}$/", 
                "address" => "/^[\d\p{L}\-,\.' ]{1,256}$/u", "postalCode" => "/^\d{5}$/", 
                "city" => "/^[\p{L}\-]{1,32}$/u", "phoneNumber" => "/^0\d{9}$/", "job" => "/^[\p{L}\-,\.' ]{1,128}$/u",
                "message" => "/^[\d\p{L}\-,\.' ]{1,512}$/u"
            ];

            // définition des tableaux permettant de génerer une message d'erreur différent selon l'entrée
            $allDataName = [
                "name" => "nom", "firstName" => "prénom", "sex" => "sexe", "birthDate" => "date de naissance", "mail" => "mail", 
                "confirmationMail" => "mail de confirmation",  "password" => "mot de passe", "confirmationPassword" => "mot de passe de confirmation", 
                "address" => "adresse", "postalCode" => "code postal", "city" => "ville", "phoneNumber" => "numéro de téléphone", 
                "job" => "metier", "topic" => "sujet", "contactDate" => "date de contact", "message" => "message"
            ];
            $allAllowedCharacter = [
                "name" => "les lettres, -", "firstName" => "les lettres, -", "password" => "les minuscules, majuscules, chiffres, -!@#$%_, avec un de chaque minimum 7 caracter",
                "address" => "les lettres, chiffres, -.,'", "postalCode" => "5 chiffes", "city" => "les lettres, -'", "phoneNumber" => "10 chiffes dont le premier est 0", 
                "job" => "les lettres, les chiffres, -'", "message" => "les lettres, chiffres, -.,'"
            ];

            $formNameUC = ucwords($formName);

            $errorSessionName = "errorMessage".$formNameUC;
            $submitKey = "submit".$formNameUC;
            $_SESSION[$errorSessionName] = [$submitKey => "", "allEmptyField" => ""];
            $correctInputSessionName = "correctInput".$formNameUC;
            unset($formInputs[$submitKey]);

            $errorNumber = 0;
            
            // boucle sur le tableau concerné par la verification grace à un nom de variable variable
            foreach($$keySet as $nameInput)
            {
                $_SESSION[$errorSessionName][$nameInput] = "";
                if(isset($formInputs[$nameInput])) // verification si le champ est set et génération d'un message d'erreur si ce n'est pas le cas
                {  
                    if($nameInput !== "sex" && $formInputs[$nameInput] === "") // verification si le champ est vide et génération d'un message d'erreur si c'est le cas
                    {
                        $_SESSION[$errorSessionName][$nameInput] = "Veuillez remplir votre {$allDataName[$nameInput]}.";
                        $errorNumber += 1;
                    }
                    else
                    {
                        $dataValueValid = valid_data($formInputs[$nameInput]);
                        if($dataValueValid !== $formInputs[$nameInput]) // verification de la validité de l'entré notament les caractères html
                        {
                            unset( $_SESSION[$errorSessionName]);
                            $_SESSION[$errorSessionName][$submitKey] = "Les données envoyées par le formulaire ne sont pas conforment.";
                            $errorNumber += 1;
                            break;
                        }
                        else
                        {
                            $formInputs[$nameInput] = trim($dataValueValid);

                            if(in_array($nameInput, $checkWithPatternKeys, true)) // verification pour tous les champs se verifiant à l'aide d'un pattern
                            {
                                $errorNumber += checkWithPattern($nameInput, $formInputs[$nameInput], $patterns[$nameInput], $allDataName[$nameInput], $allAllowedCharacter[$nameInput], $errorSessionName, $correctInputSessionName);
                            }
                            // verification des autres champs devant être faite individuellement 
                            else if($nameInput === "sex")
                            {
                                if(!($formInputs[$nameInput] === "Man" || $formInputs[$nameInput] === "Woman" || $formInputs[$nameInput] === "Other"))
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Une erreur est survenu. Veuillez indiquer votre {$allDataName[$nameInput]}.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                                }
                            }
                            else if($nameInput === "birthDate")
                            {
                                if(!(valid_date_inf($formInputs[$nameInput], 12))) //verification de la date de naissance
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Vous n'avez pas correctement indiqué votre {$allDataName[$nameInput]}. \nVous devez avoir au minimum 12 ans.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                                }
                            }
                            else if($nameInput === "mail")
                            {
                                if(!filter_var($formInputs[$nameInput], FILTER_VALIDATE_EMAIL)) // verification du mail faite avec un filtre
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Veuillez indiquer une adresse email valide.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                                }
                            }
                            else if(($formName === "registration" || $formName === "profile") && $nameInput === "confirmationMail")
                            {
                                if(!filter_var($formInputs[$nameInput], FILTER_VALIDATE_EMAIL)) // verification du mail de confirmation avec le filtre
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Veuillez indiquer une adresse email valide.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                    
                                    if(!(isset($formInputs["mail"]) && $formInputs["mail"] === $formInputs["confirmationMail"])) // verification de l'égalité entre le mail et le mail de confirmation
                                    {
                                        $_SESSION[$errorSessionName][$nameInput] = "Les deux adresses email ne correspondent pas.";
                                        $errorNumber += 1;
                                    }
                                }
                            }
                            else if($formName === "registration" && $nameInput === "confirmationPassword")
                            {
                                if(!preg_match($patterns["password"], $formInputs[$nameInput]) === 0) // verification du mot de passe de confirmation avec une expression regulière
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Veuillez indiquer un mot de passe valide.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                    
                                    if(!(isset($formInputs["password"]) && $formInputs["password"] === $formInputs["confirmationPassword"])) // verification de l'égalité entre le mot de passe et le mot de passe de confirmation
                                    {
                                        $_SESSION[$errorSessionName][$nameInput] = "Les deux mots de passe ne correspondent pas.";
                                        $errorNumber += 1;
                                    }
                                }
                            }
                            else if($nameInput === "topic") //verification du sujet pour le formulaire de contact
                            {
                                if(!($formInputs[$nameInput] === "Remboursement" || $formInputs[$nameInput] === "Livraison" || $formInputs[$nameInput] === "Autre"))
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Une erreur est survenu. Veuillez indiquer votre {$allDataName[$nameInput]}.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                                }
                            }
                            else if($nameInput === "contactDate")
                            {
                                if(!(valid_date_sup($formInputs[$nameInput], 1))) //verification de la date de contact
                                {
                                    $_SESSION[$errorSessionName][$nameInput] = "Vous n'avez pas correctement indiqué votre {$allDataName[$nameInput]}. \nLe délai doit être d'un an maximum.";
                                    $errorNumber += 1;
                                }
                                else
                                {
                                    $_SESSION[$correctInputSessionName][$nameInput] = $formInputs[$nameInput];
                                }
                            }
                        }
                        
                    }
                }
                else
                {
                    /* l'entré sex est traité differement car vu que c'est un bouton radio, contrairement au champs textuels 
                    qui même non rempli sont quant même set avec des valeur vide, l'input radio n'est pas set s'in n'est pas 
                    rempli */
                    if($nameInput === "sex")
                    {
                        $_SESSION[$errorSessionName][$nameInput] = "Veuillez indiquer votre {$allDataName[$nameInput]}.";
                        $errorNumber += 1;
                    }
                    else{
                        $_SESSION[$errorSessionName][$submitKey] = "Erreur lors de l'envoi du formulaire.";
                        $errorNumber += 1;
                    }
                }
            }
            return $errorNumber;
        }
        else
        {
            return -1;
        }

    }
?>