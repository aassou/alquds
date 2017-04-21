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
    $parkingManager = new ParkingManager($pdo);
    //obj and vars
    $idProjet = htmlentities($_POST['idProjet']);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['nombrePlace']) ){
            $nombrePlaces = htmlentities($_POST['nombrePlace']);
            $lastCode = 1;
            if ( $parkingManager->getLastCodeByIdProjet($idProjet) > 0 ) {
                $lastCode = $parkingManager->getLastCodeByIdProjet($idProjet)+1;
            } 
            for ( $i=0; $i<$nombrePlaces; $i++ ) {
                $code = $lastCode++;
                echo $code."<br>";
                $status = "Disponible";
                $idProjet = htmlentities($_POST['idProjet']);
                $idContrat = NULL;
                $createdBy = $_SESSION['userMerlaTrav']->login();
                $created = date('Y-m-d h:i:s');
                //create object
                $parking = new Parking(array(
                    'code' => $code,
                    'status' => $status,
                    'idProjet' => $idProjet,
                    'idContrat' => $idContrat,
                    'created' => $created,
                    'createdBy' => $createdBy
                ));
                //add it to db
                $parkingManager->add($parking);    
            }
            $actionMessage = "Opération Valide : Parking Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout parking : Vous devez remplir le nombre de places du parking.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        if(!empty($_POST['idParking'])){
            $idParking = htmlentities($_POST['idParking']);
			$status = htmlentities($_POST['status']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $parking = new Parking(array(
				'id' => $idParking,
				'status' => $status,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $parkingManager->update($parking);
            $actionMessage = "Opération Valide : Parking Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Parking : Ce parking n'existe pas.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idParking = htmlentities($_POST['idParking']);
        $parkingManager->delete($idParking);
        $actionMessage = "Opération Valide : Parking supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['parking-action-message'] = $actionMessage;
    $_SESSION['parking-type-message'] = $typeMessage;
    header('Location:../sous-sol.php?idProjet='.$idProjet);

