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
    include('lib/pagination.php');
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ) {
        if( isset($_GET['idProjet']) ){
           $idProjet = $_GET['idProjet'];   
        }
        //destroy contrat-form-data session
        if ( isset($_SESSION['contrat-form-data']) ) {
            unset($_SESSION['contrat-form-data']);
        }
        $projetManager = new ProjetManager($pdo);
        $clientManager = new ClientManager($pdo);
        $contratManager = new ContratManager($pdo);
        $operationManager = new OperationManager($pdo);
        $compteBancaireManager = new CompteBancaireManager($pdo);
        
        /*$codeContrat = $_GET['codeContrat'];
        $comptesBancaires = $compteBancaireManager->getCompteBancaires();
        $contrat = $contratManager->getContratByCode($codeContrat);
        
        
        $projet = $projetManager->getProjetById($contrat->idProjet());
        $client = $clientManager->getClientById($contrat->idClient());
        $sommeOperations = $operationManager->sommeOperations($contrat->id());
        $biens = "";
        $niveau = "";
        if($contrat->typeBien()=="appartement"){
            $appartementManager = new AppartementManager($pdo);
            $biens = $appartementManager->getAppartementById($contrat->idBien());
            $niveau = $biens->niveau();
        }
        else if($contrat->typeBien()=="localCommercial"){
            $locauxManager = new LocauxManager($pdo);
            $biens = $locauxManager->getLocauxById($contrat->idBien());
        }*/
        $mois = $_GET['mois'];
        $annee = $_GET['annee'];
        //$operations = "";
        //test the locaux object number: if exists get operations else do nothing
        //$operationsNumber = $operationManager->getOpertaionsNumberByIdContrat($contrat->id());
        $operations = $operationManager->getOperationsValideesByMonthYear($mois, $annee);
        /*if($operationsNumber != 0){
            $operations = $operationManager->getOperationsByIdContrat($contrat->id());  
        }*/
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>ImmoERP - Management Application</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/metro.css" rel="stylesheet" />
    <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/style_responsive.css" rel="stylesheet" />
    <link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
    <link href="assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
    <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <?php include("include/top-menu.php"); ?>   
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container row-fluid sidebar-closed">
        <!-- BEGIN SIDEBAR -->
        <?php include("include/sidebar.php"); ?>
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE -->
        <div class="page-content">
            <!-- BEGIN PAGE CONTAINER-->            
            <div class="container-fluid">
                <!-- BEGIN PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->           
                        <h3 class="page-title">
                            Liste des paiements clients validés
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-bar-chart"></i>
                                <a href="status.php">Les états</a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-money"></i>
                                <a href="operations-status-group.php">Les états des paiements clients</a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a><strong><?= $mois."/".$annee ?></strong></a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="row-fluid">
                    <div class="span12">
                    <div class="portlet box light-grey" id="detailsReglements">
                        <div class="portlet-title">
                            <h4>Liste des paiements clients validés</h4>
                            <div class="tools">
                                <a href="javascript:;" class="reload"></a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="clearfix">
                                <?php 
                                 if( isset($_SESSION['operation-action-message']) 
                                 and isset($_SESSION['operation-type-message']) ){
                                    $message = $_SESSION['operation-action-message'];
                                    $typeMessage = $_SESSION['operation-type-message'];
                                 ?>
                                    <div class="alert alert-<?= $typeMessage ?>">
                                        <button class="close" data-dismiss="alert"></button>
                                        <?= $message ?>     
                                    </div>
                                 <?php 
                                 } 
                                 unset($_SESSION['operation-action-message']);
                                 unset($_SESSION['operation-type-message']);
                                ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th><span class="hidden-phone">Action</span></th>
                                        <th>Client</th>
                                        <th>Projet</th>
                                        <th class="hidden-phone">DOpér</th>
                                        <th class="hidden-phone">DRégl</th>
                                        <th class="hidden-phone">ModPaimnt</th>
                                        <th class="hidden-phone">Compte</th>
                                        <th class="hidden-phone">N°Opé</th>
                                        <th>Montant</th>
                                        <th class="hidden-phone">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($operations as $operation){
                                        $status = "";
                                        $action = "";
                                        $idContrat = $operation->idContrat();
                                        $contrat = $contratManager->getContratById($idContrat);
                                        $nomProjet = $projetManager->getProjetById($contrat->idProjet())->nom();
                                        $nomClient = $contratManager->getClientNameByIdContract($operation->idContrat());
                                        if ( $operation->status() == 0 ) {
                                            $action = '<a class="btn grey mini"><i class="icon-off"></i></a>'; 
                                            if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                                $status = '<a class="btn red mini" href="#validateOperation'.$operation->id().'" data-toggle="modal" data-id="'.$operation->id().'"><i class="icon-pause"></i>&nbsp;Non validé</a>';  
                                            } 
                                            else{
                                                $status = '<a class="btn red mini"><i class="icon-pause"></i>&nbsp;Non validé</a>';
                                            } 
                                        } 
                                        else {
                                            if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                                $status = '<a class="btn blue mini" href="#cancelOperation'.$operation->id().'" data-toggle="modal" data-id="'.$operation->id().'"><i class="icon-ok"></i>&nbsp;Validé</a>';
                                                $action = '<a class="btn green mini" href="#hideOperation'.$operation->id().'" data-toggle="modal" data-id="'.$operation->id().'"><i class="icon-off"></i></a>';   
                                            }
                                            else {
                                                $status = '<a class="btn blue mini"><i class="icon-ok"></i>&nbsp;Validé</a>';
                                                $action = '<a class="btn grey mini"><i class="icon-off"></i></a>'; 
                                            }
                                        }
                                    ?>      
                                    <tr class="odd gradeX">
                                        <td><?= $action ?></td>
                                        <td><?= $nomClient ?></td>
                                        <td><?= $nomProjet ?></td>
                                        <td class="hidden-phone"><?= date('d/m/Y', strtotime($operation->date())) ?></td>
                                        <td class="hidden-phone"><?= date('d/m/Y', strtotime($operation->dateReglement())) ?></td>
                                        <td class="hidden-phone"><?= $operation->modePaiement() ?></td>
                                        <td class="hidden-phone"><?= $operation->compteBancaire() ?></td>
                                        <td class="hidden-phone"><?= $operation->numeroCheque() ?></td>
                                        <td><?= number_format($operation->montant(), 2, ',', ' ') ?>&nbsp;DH</td>
                                        <td class="hidden-phone"><?= $status ?></td>
                                    </tr>   
                                    <!-- validateOperation box begin-->
                                    <div id="validateOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Valider Paiement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="controller/OperationActionController.php" method="post">
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="validate" />
                                                    <input type="hidden" name="source" value="operations-status" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn blue" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- validateOperation box end -->
                                    <!-- cancelOperation box begin-->
                                    <div id="cancelOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Annuler Paiement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="controller/OperationActionController.php" method="post">
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="cancel" />
                                                    <input type="hidden" name="source" value="operations-status" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- cancelOperation box end -->
                                    <!-- hideOperation box begin-->
                                    <div id="hideOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Retirer Paiement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="controller/OperationActionController.php" method="post">
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="hide" />
                                                    <input type="hidden" name="source" value="operations-status" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn green" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- hideOperation box end -->  
                                    <!-- delete box begin-->
                                    <div id="deleteOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Supprimer Réglement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="controller/OperationActionController.php" method="post">
                                                <p>Êtes-vous sûr de vouloir supprimer ce réglement ?</p>
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="delete" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- delete box end --> 
                                    <?php
                                    }//end of loop
                                    ?>
                                </tbody>
                            </table>
                        </div>
                     </div>
                   </div>
                </div>
                <!-- END PAGE CONTENT -->
            </div>
            <!-- END PAGE CONTAINER-->
        </div>
        <!-- END PAGE -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="footer">
        2015 &copy; ImmoERP. Management Application.
        <div class="span pull-right">
            <span class="go-top"><i class="icon-angle-up"></i></span>
        </div>
    </div>
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS -->
    <!-- Load javascripts at bottom, this will reduce page load time -->
    <script src="assets/js/jquery-1.8.3.min.js"></script>   
    <script src="assets/breakpoints/breakpoints.js"></script>   
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>        
    <script src="assets/js/jquery.blockui.js"></script>
    <script src="assets/js/jquery.cookie.js"></script>
    <script src="assets/fancybox/source/jquery.fancybox.pack.js"></script>
    <script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>    
    <script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
    <!-- ie8 fixes -->
    <!--[if lt IE 9]>
    <script src="assets/js/excanvas.js"></script>
    <script src="assets/js/respond.js"></script>
    <![endif]-->    
    <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
    <script src="assets/js/app.js"></script>        
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            App.setPage("table_managed");
            $('.hidenBlock').hide();
            App.init();
            function blinker() {
                $('.blink_me').fadeOut(500);
                $('.blink_me').fadeIn(500);
            }
            
            setInterval(blinker, 1500);
        });
    </script>
</body>
<!-- END BODY -->
</html>
<?php
}
/*else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
    header('Location:dashboard.php');
}*/
else{
    header('Location:index.php');    
}

?>