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
    //This var contains result message of CRUD action
    $actionMessage = "";
    $typeMessage = "";

    //Component Class Manager

    $clientAttenteManager = new ClientAttenteManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['nom']) ){
			$nom = htmlentities($_POST['nom']);
            $tel = htmlentities($_POST['tel']);
			$date = htmlentities($_POST['date']);
			$bien = htmlentities($_POST['bien']);
			$prix = htmlentities($_POST['prix']);
			$superficie = htmlentities($_POST['superficie']);
			$emplacementVente = htmlentities($_POST['emplacementVente']);
			$emplacementAchat = htmlentities($_POST['emplacementAchat']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $clientAttente = new ClientAttente(array(
				'nom' => $nom,
				'tel' => $tel,
				'date' => $date,
				'bien' => $bien,
				'prix' => $prix,
				'superficie' => $superficie,
				'emplacementVente' => $emplacementVente,
				'emplacementAchat' => $emplacementAchat,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $clientAttenteManager->add($clientAttente);
            $actionMessage = "Opération Valide : ClientAttente Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout clientAttente : Vous devez remplir le champ 'nom'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idClientAttente = htmlentities($_POST['idClientAttente']);
        if(!empty($_POST['nom'])){
			$nom = htmlentities($_POST['nom']);
            $tel = htmlentities($_POST['tel']);
			$date = htmlentities($_POST['date']);
			$bien = htmlentities($_POST['bien']);
			$prix = htmlentities($_POST['prix']);
			$superficie = htmlentities($_POST['superficie']);
			$emplacementVente = htmlentities($_POST['emplacementVente']);
			$emplacementAchat = htmlentities($_POST['emplacementAchat']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $clientAttente = new ClientAttente(array(
				'id' => $idClientAttente,
				'nom' => $nom,
				'tel' => $tel,
				'date' => $date,
				'bien' => $bien,
				'prix' => $prix,
				'superficie' => $superficie,
				'emplacementVente' => $emplacementVente,
				'emplacementAchat' => $emplacementAchat,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $clientAttenteManager->update($clientAttente);
            $actionMessage = "Opération Valide : ClientAttente Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification ClientAttente : Vous devez remplir le champ 'nom'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idClientAttente = htmlentities($_POST['idClientAttente']);
        $clientAttenteManager->delete($idClientAttente);
        $actionMessage = "Opération Valide : ClientAttente supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['clientAttente-action-message'] = $actionMessage;
    $_SESSION['clientAttente-type-message'] = $typeMessage;
    header('Location:../clients-attente.php');

