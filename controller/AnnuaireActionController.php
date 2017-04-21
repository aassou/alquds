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

    $annuaireManager = new AnnuaireManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['nom']) and !empty($_POST['telephone1']) ){
			$nom = htmlentities($_POST['nom']);
			$description = htmlentities($_POST['description']);
			$telephone1 = htmlentities($_POST['telephone1']);
			$telephone2 = htmlentities($_POST['telephone2']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $annuaire = new Annuaire(array(
				'nom' => $nom,
				'description' => $description,
				'telephone1' => $telephone1,
				'telephone2' => $telephone2,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $annuaireManager->add($annuaire);
            $actionMessage = "Opération Valide : Numéro Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout Annuaire : Vous devez remplir le champ 'Nom' et 'Téléphone'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idAnnuaire = htmlentities($_POST['idAnnuaire']);
        if(!empty($_POST['nom'])){
			$nom = htmlentities($_POST['nom']);
			$description = htmlentities($_POST['description']);
			$telephone1 = htmlentities($_POST['telephone1']);
			$telephone2 = htmlentities($_POST['telephone2']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $annuaire = new Annuaire(array(
				'id' => $idAnnuaire,
				'nom' => $nom,
				'description' => $description,
				'telephone1' => $telephone1,
				'telephone2' => $telephone2,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $annuaireManager->update($annuaire);
            $actionMessage = "Opération Valide : Annuaire Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Annuaire : Vous devez remplir le champ 'Nom' et 'Téléphone'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idAnnuaire = htmlentities($_POST['idAnnuaire']);
        $annuaireManager->delete($idAnnuaire);
        $actionMessage = "Opération Valide : Numéro supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['annuaire-action-message'] = $actionMessage;
    $_SESSION['annuaire-type-message'] = $typeMessage;
    header('Location:../annuaire.php');

