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

    $clientClassementManager = new ClientClassementManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['nom']) ){
			$nom = htmlentities($_POST['nom']);
			$classement = htmlentities($_POST['classement']);
			$remarque = htmlentities($_POST['remarque']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $clientClassement = new ClientClassement(array(
				'nom' => $nom,
				'classement' => $classement,
				'remarque' => $remarque,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $clientClassementManager->add($clientClassement);
            $actionMessage = "Opération Valide : ClientClassement Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout clientClassement : Vous devez remplir le champ 'nom'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idClientClassement = htmlentities($_POST['idClientClassement']);
		$classement = htmlentities($_POST['classement']);
		$remarque = htmlentities($_POST['remarque']);
		$updatedBy = $_SESSION['userMerlaTrav']->login();
        $updated = date('Y-m-d h:i:s');
        $clientClassement = new ClientClassement(array(
			'id' => $idClientClassement,
			'classement' => $classement,
			'remarque' => $remarque,
			'updated' => $updated,
        	'updatedBy' => $updatedBy
		));
        $clientClassementManager->update($clientClassement);
        $actionMessage = "Opération Valide : ClientClassement Modifié(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idClientClassement = htmlentities($_POST['idClientClassement']);
        $clientClassementManager->delete($idClientClassement);
        $actionMessage = "Opération Valide : ClientClassement supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['clientClassement-action-message'] = $actionMessage;
    $_SESSION['clientClassement-type-message'] = $typeMessage;
    header('Location:../clients-classement.php');

