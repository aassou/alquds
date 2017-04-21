<?php
//classes loading begin
    function classLoad ($myClass) {
        if(file_exists('model/'.$myClass.'.php')){
            include('model/'.$myClass.'.php');
        }
        elseif(file_exists('controller/'.$myClass.'.php')){
            include('controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('config.php');  
    //process begin
    if ( isset($_POST['idContrat']) ) {
        //load classes managers
        $clientManager = new ClientManager($pdo);
        $contratManager = new ContratManager($pdo);
        $projetManager = new ProjetManager($pdo);
        $appartementManager = new AppartementManager($pdo);
        $locauxManager = new LocauxManager($pdo);
        $operationManager = new OperationManager($pdo);
        //begin processing
        $idContrat = htmlentities($_POST['idContrat']);
        $requete = "SELECT * FROM t_operation WHERE idContrat = '".$idContrat."'";
        // exécution de la requête
        $resultat = $pdo->query($requete) or die(print_r($bdd->errorInfo()));
        // résultats
        while ( $operation = $resultat->fetch(PDO::FETCH_ASSOC)) {
            $status = "Non Validé";
            $checkbox = '<input onclick="assignIdOperation()" class="span1" type="radio" name="operationValue" value="'.$operation['id'].'" />';
            $classStatus = "input-error-text";
            if ( $operation['status'] != 0 ) {
                $status = "Validé";
                $classStatus = "input-success-text";
                $checkbox = '<input class="span1" type="hidden" />';
            }
            $res = '<div class="controls controls-row">'.
                        '<input disabled="disabled" class="span2 m-wrap '.$classStatus.'" type="text" value="'.date('d/m/y', strtotime($operation['date'])).'" />'.
                        '<input disabled="disabled" class="span2 m-wrap '.$classStatus.'" type="text" value="'.date('d/m/y' , strtotime($operation['dateReglement'])).'" />'.
                        '<input disabled="disabled" class="span3 m-wrap '.$classStatus.'" type="text" value="'.number_format($operation['montant'], 2, ',', ' ').'" />'.
                        '<input disabled="disabled" class="span2 m-wrap '.$classStatus.'" type="text" value="'.$operation['compteBancaire'].'" />'.
                        '<input disabled="disabled" class="span2 m-wrap '.$classStatus.'" type="text" value="'.$operation['numeroCheque'].'" />'.
                        $checkbox.
                    '</div>';
            echo $res;
        }
    }
?>