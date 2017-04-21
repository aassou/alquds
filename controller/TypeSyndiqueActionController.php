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

    $typeSyndiqueManager = new TypeSyndiqueManager($pdo);
	//Action Add Processing Begin
    	if($action == "add"){
        if( !empty($_POST['designation']) ){
			$designation = htmlentities($_POST['designation']);
			$frais = htmlentities($_POST['frais']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $typeSyndique = new TypeSyndique(array(
				'designation' => $designation,
				'frais' => $frais,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $typeSyndiqueManager->add($typeSyndique);
            $actionMessage = "Opération Valide : TypeSyndique Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout typeSyndique : Vous devez remplir le champ 'designation'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idTypeSyndique = htmlentities($_POST['idTypeSyndique']);
        if(!empty($_POST['designation'])){
			$designation = htmlentities($_POST['designation']);
			$frais = htmlentities($_POST['frais']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            			$typeSyndique = new TypeSyndique(array(
				'id' => $idTypeSyndique,
				'designation' => $designation,
				'frais' => $frais,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $typeSyndiqueManager->update($typeSyndique);
            $actionMessage = "Opération Valide : TypeSyndique Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification TypeSyndique : Vous devez remplir le champ 'designation'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idTypeSyndique = htmlentities($_POST['idTypeSyndique']);
        $typeSyndiqueManager->delete($idTypeSyndique);
        $actionMessage = "Opération Valide : TypeSyndique supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['typeSyndique-action-message'] = $actionMessage;
    $_SESSION['typeSyndique-type-message'] = $typeMessage;
    header('Location:../file-name-please.php');

