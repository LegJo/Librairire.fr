<?php
    session_start();
    require_once('./manage_files.php');

    //récupération du produit en html
    function getHtmlProduct($code_prod, $num)
    {
        $infos=getproductAllInfo($code_prod);
        $price=$infos['prix'];
        return '
        <div class="product_poppan '.$code_prod.'" style="width: max-content;" id="popproduct'.$num.'">
            <a href="./produit.php?pdt='.$code_prod.'" id="linkpdt'.$code_prod.'">
                 <img class="ppp_img ppp_child pointer" src="./img/img_products/'.$infos["pic_name"].'" alt="'.$infos["pic_name"].'">
            </a>
            <div class="popproduct_info ppp_child" id="qt'.$code_prod.'">
                <h4>'.$infos["Titre"].'</h4>
                '.$infos["prix"].'€        
            </div>
        </div>
        ';
    }

    //calcul de l'age
    function calculate_age($date){
        $time = strtotime($date);
        if($time === false){
          return '';
        }
     
        $year_diff = '';
        $date = date('Y-m-d', $time);
        list($year,$month,$day) = explode('-',$date);
        $year_diff = date('Y') - $year;
        $month_diff = date('m') - $month;
        $day_diff = date('d') - $day;
        if ($day_diff < 0 || $month_diff < 0) $year_diff--;
     
        return $year_diff;
    }

    //verification de si l'user correspond aux parametres entré
    function recipientCheck($recipientChoice, $age)
    {
        foreach($recipientChoice as $choice)
        {
            if($choice === "all"){
                return true;
            }
            else if($choice === "minus18" && $age < 18){
                return true;
            }
            else if($choice === "18to35" && $age >= 18 && $age <= 35){
                return true;
            }
            else if($choice === "18to35" && $age >= 35 && $age <= 55){
                return true;
            }
            else if($choice === "plus55" && $age > 55){
                return true;
            }
            else if($choice === "half" && mt_rand(0, 1) === 0){
                return true;
            }
            else if($choice === "third" && mt_rand(0, 2) === 0){
                return true;
            }
            
        }
        return false;
    }

    //création du corps du mail avec le message et les produits sélectionnés
    function createMail(array $messageTab, array $produitsTab, string $messageHtml)
    {
        $nbProduitSet = 1;
        $ajoutPdt = false;
        foreach($messageTab as $key => $textPart)
        {
            if($textPart === ''){
                $messageHtml .= $textPart;
            }
            else{
                if($ajoutPdt)
                {
                    $messageHtml .= "</div>";
                    $ajoutPdt = false;
                }
                $messageHtml .= "<p style=\"display: inline;\">".nl2br($textPart)."</p>";
            }

            if($nbProduitSet <= count($produitsTab))
            {
                if(!$ajoutPdt)
                {
                    $messageHtml .= "<div style=\"display: flex; flex-direction: row; justify-content: space-around;flex-wrap: wrap;width: 650px;\">";
                }
                $messageHtml .= getHtmlProduct($produitsTab[$key], $nbProduitSet);
                $nbProduitSet++;
                $ajoutPdt = true;
            }
        }

        for($i = ($nbProduitSet-1); $i < count($produitsTab); $i++)
        {
            $messageHtml .= getHtmlProduct($produitsTab[$i], $i+1);
            $ajoutPdt = true;
        }

        if($ajoutPdt)
        {
            $messageHtml .= "</div>";
        }

        return $messageHtml;
    }

    //renvoi le corps du mail pour l'ajax qui crée l 'aperçu du mail
    if(isset($_POST['fct']) && $_POST['fct'] === 'mailSend' && isset($_POST['message']) && isset($_POST['pdt']))
    {
        $message = $_POST['message'];
        $produits = $_POST['pdt'];
        $messageSansBalise = str_replace('<pdt>', '', $message);
        $messageVerif = htmlspecialchars($messageSansBalise);
        $produitsVerif = htmlspecialchars($produits);
        if($messageVerif !== $messageSansBalise || $produitsVerif !== $produits)
        {
            echo "Erreur";
            return 0;
        }

        $messageAjax = "";
        if($produits !== ""){
            $pdtsTab = explode(',', $produits);
            $msgTab = explode('<pdt>', $message);
        }
        else{
            $pdtsTab = [];
            $msgTab[] = $message;
        }

        $messageAjax = createMail($msgTab, $pdtsTab, $messageAjax);

        echo $messageAjax;
        return 0;
    }
    // lors de l'envoi du mail
    else if(isset($_POST['submitMail'])&& isset($_POST['recipientType']) && isset($_POST['recipientChoice']) && isset($_POST['title']) && isset($_POST['messageMail']) && isset($_POST['selectProduits']))
    {
        $recipientType = $_POST['recipientType'];
        $recipientChoice = $_POST['recipientChoice'];
        $message = $_POST['messageMail'];

        $produits = implode(',', $_POST['selectProduits']);
        $messageSansBalise = str_replace('<pdt>', '', $message);
        $messageVerif = htmlspecialchars($messageSansBalise);
        $produitsVerif = htmlspecialchars($produits);
        if($messageVerif !== $messageSansBalise || $produitsVerif !== $produits)
        {
            echo "Erreur";
            return 0;
        }

        $messageSend = "";
        if($produits !== "" && $produits != "aucun"){
            $pdtsTab = explode(',', $produits);
            $msgTab = explode('<pdt>', $message);
        }
        else{
            $pdtsTab = [];
            $msgTab[] = $message;
        }
        
        $messageSend .= "<div>";
        $messageSend = createMail($msgTab, $pdtsTab, $messageSend);
        $messageSend .= "</div>";

        $usersJSON = file_get_contents(ROOT.FILE_USERS_JSON);
        $usersTab = json_decode($usersJSON, true);

        $destinataireTab = [];

        //sélection des users correspondant aux parametres entrés
        foreach($usersTab as $user)
        {
            $codeAdmin = substr($user['userCode'], 1, 1);
            $age = calculate_age($user['birthDate']);
            if($recipientType === "user" && $codeAdmin === "1")
            {
                if(recipientCheck($recipientChoice, $age))
                {
                    $destinataireTab[] = $user['mail'];
                }
            }
            else if($recipientType === "admin" && $codeAdmin === "0")
            {
                if(recipientCheck($recipientChoice, $age))
                {
                    $destinataireTab[] = $user['mail'];
                }
            }
            else if($recipientType === "theTwo")
            {
                if(recipientCheck($recipientChoice, $age))
                {
                    $destinataireTab[] = $user['mail'];
                }
            }
            else{
                echo "Erreur";
            }

        }

        $destinataire = implode(', ', $destinataireTab);
        $objet = $_POST['title'];

        $headers  = "";
        $headers .= "From: librairireprojet@gmail.com\r\n";
        $headers .= "MIME-version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        /*
        $result = mail($destinataire, $objet, $messageSend, $headers);
        
        if( !$result ){
            echo "L'envoi du mail a échoué";
        }
        else{
            echo "C bon";
        }
        */

        $mail = $headers."<p>Destinataires: ".$destinataire."</p><p>Objet: ".$objet."</p><br>".$messageSend;
        $_SESSION['mail'] = $mail;
        
        header('Location: ../recapMail.php', true, 301);
        exit;
        
    }

?>