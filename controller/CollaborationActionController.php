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

    $collaborationManager = new CollaborationManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['titre']) ){
			$titre = htmlentities($_POST['titre']);
			$description = htmlentities($_POST['description']);
            $status = htmlentities($_POST['status']);
            $duree = htmlentities($_POST['duree']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $collaboration = new Collaboration(array(
				'titre' => $titre,
				'description' => $description,
				'status' => $status,
				'duree' => $duree,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $collaborationManager->add($collaboration);
            $actionMessage = "Opération Valide : Collaboration Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout collaboration : Vous devez remplir le champ 'titre'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idCollaboration = htmlentities($_POST['idCollaboration']);
        if(!empty($_POST['titre'])){
			$titre = htmlentities($_POST['titre']);
			$description = htmlentities($_POST['description']);
            $status = htmlentities($_POST['status']);
            $duree = htmlentities($_POST['duree']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $collaboration = new Collaboration(array(
				'id' => $idCollaboration,
				'titre' => $titre,
				'description' => $description,
				'status' => $status,
				'duree' => $duree,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $collaborationManager->update($collaboration);
            $actionMessage = "Opération Valide : Collaboration Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Collaboration : Vous devez remplir le champ 'titre'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idCollaboration = htmlentities($_POST['idCollaboration']);
        $collaborationManager->delete($idCollaboration);
        $actionMessage = "Opération Valide : Collaboration supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['collaboration-action-message'] = $actionMessage;
    $_SESSION['collaboration-type-message'] = $typeMessage;
    header('Location:../collaboration.php');

