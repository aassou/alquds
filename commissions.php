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
    if( isset($_SESSION['userMerlaTrav']) ){
        //class managers
        $commissionManager = new CommissionManager($pdo);
        $projetManager = new ProjetManager($pdo);
        $contratManager = new ContratManager($pdo);
        $clientsManager = new ClientManager($pdo);
        //objs and vars
        $commissions = $commissionManager->getCommissions();
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
                            Gestion des commissions
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-bar-chart"></i>
                                <a href="status.php">Les états</a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-thumbs-up"></i>
                                <a>Gestion des commissions</a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <!-- COMMSSIONS BEGIN -->
                         <?php 
                         if( isset($_SESSION['commission-action-message']) 
                         and isset($_SESSION['commission-type-message']) ){
                            $message = $_SESSION['commission-action-message'];
                            $typeMessage = $_SESSION['commission-type-message'];
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php 
                         } 
                         unset($_SESSION['commission-action-message']);
                         unset($_SESSION['commission-type-message']);
                        ?>
                         <div class="portlet box light-grey" id="commissions">
                            <div class="portlet-title">
                                <h4>Détails commissions</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th class="hidden-phone">Action</th>
                                            <th>Nom</th>
                                            <th class="hidden-phone">Description</th>
                                            <th>Montant</th>
                                            <th class="hidden-phone">Projet</th>
                                            <th class="hidden-phone">Bien</th>
                                            <th class="hidden-phone">Date</th>
                                            <th class="hidden-phone">état</th>
                                            <th class="hidden-desktop hidden-tablet">Détails</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($commissions as $commission){
                                            //get projet, bien and client informations
                                            $contrat = $contratManager->getContratByCode($commission->codeContrat());
                                            $projet = $projetManager->getProjetById($contrat->idProjet());
                                            $typeBien = $contrat->typeBien();
                                            $bien = "";
                                            if ( $typeBien == "localCommercial" ) {
                                                $locauxManager = new LocauxManager($pdo);
                                                $bien = $locauxManager->getLocauxById($contrat->idBien());
                                                $typeBien = "Local";
                                            }
                                            else {
                                                $appartementManager = new AppartementManager($pdo);
                                                $bien = $appartementManager->getAppartementById($contrat->idBien());
                                                $typeBien = "Appart";
                                            }
                                            $etatButton = "red";
                                            $etat = "Non validée";
                                            if ( $commission->etat() == "V" ) {
                                                $etatButton = "green";
                                                $etat = "Validée";
                                            } 
                                        ?>
                                        <tr class="odd gradeX">
                                            <td class="hidden-phone">
                                                <?php
                                                if ( $_SESSION['userMerlaTrav']->profil() != "consultant" ) {
                                                ?>
                                                <a href="#update<?= $commission->id() ?>" data-toggle="modal" data-id="<?= $commission->id() ?>" class="btn mini green"><i class="icon-refresh"></i></a>
                                                <?php  
                                                }
                                                ?>
                                            </td>    
                                            <td><?= $commission->commissionnaire() ?></td> 
                                            <td class="hidden-phone"><?= $commission->titre() ?></td>
                                            <td><?= number_format($commission->montant(), 2, ',', ' ') ?></td>
                                            <td class="hidden-phone"><?= $projet->nom() ?></td>
                                            <td class="hidden-phone"><?= $typeBien." - ".$bien->nom() ?></td>
                                            <td class="hidden-phone"><?= date('d/m/Y', strtotime($commission->date())) ?></td>
                                            <td class="hidden-phone"><a class="btn mini <?= $etatButton ?>"><?= $commission->etat() ?></a></td>
                                            <td class="hidden-desktop hidden-tablet"><a href="#showDetails<?= $commission->id()?>" class="btn mini blue" data-toggle="modal" data-id="<?= $commission->id()?>"><i class="icon-eye-open"></i></a></td>
                                        </tr>
                                        <!-- showDetails box begin-->
                                        <div id="showDetails<?= $commission->id()?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Détails Commission de <?= $commission->commissionnaire() ?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Projet :</strong><span class="pull-right"><?= $projet->nom() ?></span><br />
                                                <strong>Bien :</strong><span class="pull-right"><?= $typeBien." - ".$bien->nom() ?></span><br />
                                                <strong>Date :</strong><span class="pull-right"><?= date('d/m/Y', strtotime($commission->date())) ?></span><br />
                                                <strong>Montant :</strong><span class="pull-right"><?= number_format($commission->montant(), 2, ',', ' ') ?></span><br />
                                                <strong>État :</strong><span class="pull-right"><?= $etat ?></span><br />
                                                <strong>Description :</strong><span class="pull-right"><?= $commission->titre() ?></span><br />
                                            </div>
                                            <div class="modal-footer">
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <button class="btn blue" data-dismiss="modal"aria-hidden="true">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- showDetails box end -->  
                                        <!-- updateCommission box begin-->
                                        <div id="update<?= $commission->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier les informations de Commission </h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="controller/CommissionActionController.php" method="post">
                                                    <div class="control-group">
                                                        <label class="control-label">Commissionnaire</label>
                                                        <div class="controls">
                                                            <input type="text" name="commissionnaire" value="<?= $commission->commissionnaire() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Description</label>
                                                        <div class="controls">
                                                            <textarea name="titre"><?= $commission->titre() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Montant</label>
                                                        <div class="controls">
                                                            <input type="text" name="montant" value="<?= $commission->montant() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">état</label>
                                                        <div class="controls">
                                                            <select name="etat">
                                                                <option value="<?= $commission->etat() ?>"><?= $commission->etat() ?></option>
                                                                <option disabled="disabled">-------------------</option>
                                                                <option value="V">V</option>
                                                                <option value="X">X</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <input type="hidden" name="idCommission" value="<?= $commission->id() ?>" />
                                                        <input type="hidden" name="action" value="update" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- updateCommission box end -->
                                        <!-- delete box begin-->
                                        <div id="delete<?= $commission->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Commission</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal loginFrm" action="controller/CommissionActionController.php" method="post">
                                                    <p>Êtes-vous sûr de vouloir supprimer la commission de <?= $commission->commissionnaire() ?> ?</p>
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idCommission" value="<?= $commission->id() ?>" />
                                                        <input type="hidden" name="action" value="delete" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- delete box end -->     
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- COMMSSIONS END -->
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <!-- END PAGE CONTENT-->
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
            App.init();
        });
    </script>
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header("Location:index.php");
}
?>