<?php

    //classes loading begin
    function classLoad ($myClass) {
        if(file_exists('../model/'.$myClass.'.php')){
            include('../model/'.$myClass.'.php');
        }
        elseif(file_exists('../controller/'.$myClass.'.php')){
            include('../controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('../config.php');  
    include('../lib/image-processing.php');
    //classes loading end
    session_start();
    
    //post input processing
    $action = htmlentities($_POST['action']);
    $redirectLink = "commissions.php";
    //This var contains result message of CRUD action
    $actionMessage = "";
    $typeMessage = "";

    //Component Class Manager

    $commissionManager = new CommissionManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['titre']) ){
			$titre = htmlentities($_POST['titre']);
			$commissionnaire = htmlentities($_POST['commissionnaire']);
			$montant = htmlentities($_POST['montant']);
			$codeContrat = htmlentities($_POST['codeContrat']);
			$date = htmlentities($_POST['date']);
			$etat = htmlentities($_POST['etat']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $commission = new Commission(array(
				'titre' => $titre,
				'commissionnaire' => $commissionnaire,
				'montant' => $montant,
				'codeContrat' => $codeContrat,
				'date' => $date,
				'etat' => $etat,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $commissionManager->add($commission);
            $actionMessage = "Opération Valide : Commission Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout commission : Vous devez remplir le champ 'titre'.";
            $typeMessage = "error";
        }
        //test request url to set response url
        if ( isset($_POST['source']) and $_POST['source'] == "contrat" ) {
            $redirectLink = "Location:../contrat.php?codeContrat=$codeContrat#commissions";
        }
        else {
            $redirectLink = "Location:../commissions.php";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idCommission = htmlentities($_POST['idCommission']);
        if(!empty($_POST['titre'])){
			$titre = htmlentities($_POST['titre']);
			$commissionnaire = htmlentities($_POST['commissionnaire']);
			$montant = htmlentities($_POST['montant']);
			$etat = htmlentities($_POST['etat']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $commission = new Commission(array(
				'id' => $idCommission,
				'titre' => $titre,
				'commissionnaire' => $commissionnaire,
				'montant' => $montant,
				'etat' => $etat,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $commissionManager->update($commission);
            $actionMessage = "Opération Valide : Commission Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Commission : Vous devez remplir le champ 'titre'.";
            $typeMessage = "error";
        }
        //test request url to set response url
        if ( isset($_POST['source']) and $_POST['source'] == "contrat" ) {
            $idProjet = htmlentities($_POST['idProjet']);
            $redirectLink = "Location:../contrat.php?codeContrat=$codeContrat#commissions";
        }
        else {
            $redirectLink = "Location:../commissions.php";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idCommission = htmlentities($_POST['idCommission']);
        $commissionManager->delete($idCommission);
        $actionMessage = "Opération Valide : Commission supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    
    //set session informations
    $_SESSION['commission-action-message'] = $actionMessage;
    $_SESSION['commission-type-message'] = $typeMessage;
    
    //set redirection link
    header($redirectLink);

