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
    $redirectLink = "";
    //Component Class Manager

    $observationClientManager = new ObservationClientManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['description']) ){
			$description = htmlentities($_POST['description']);
			$idContrat = htmlentities($_POST['idContrat']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $observationClient = new ObservationClient(array(
				'description' => $description,
				'idContrat' => $idContrat,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $observationClientManager->add($observationClient);
            $actionMessage = "Opération Valide : ObservationClient Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout observationClient : Vous devez remplir le champ 'description'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idObservationClient = htmlentities($_POST['idObservationClient']);
        if(!empty($_POST['description'])){
			$description = htmlentities($_POST['description']);
			$idContrat = htmlentities($_POST['idContrat']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            			$observationClient = new ObservationClient(array(
				'id' => $idObservationClient,
				'description' => $description,
				'idContrat' => $idContrat,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $observationClientManager->update($observationClient);
            $actionMessage = "Opération Valide : ObservationClient Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification ObservationClient : Vous devez remplir le champ 'description'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idObservationClient = htmlentities($_POST['idObservationClient']);
        $observationClientManager->delete($idObservationClient);
        $actionMessage = "Opération Valide : ObservationClient supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    //set redirections
    if ( isset($_POST['source']) and $_POST['source'] == "contrat" ) {
        $codeContrat = htmlentities($_POST['codeContrat']);
        $redirectLink = "Location:../contrat.php?codeContrat=$codeContrat";
    }
    //set session vars
    $_SESSION['observationClient-action-message'] = $actionMessage;
    $_SESSION['observationClient-type-message'] = $typeMessage;
    header($redirectLink);

