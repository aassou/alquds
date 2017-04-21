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
        //les sources
        $clientAttenteManager = new ClientAttenteManager($pdo);
        $clientAttente = $clientAttenteManager->getClientAttentes();
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
                            Liste d'attente des clients
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-group"></i>
                                <a>Liste Attente Clients</a>
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
                        <!-- addDemande box begin-->
                        <div id="addDemande" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter Nouvelle Demande Client </h3>
                            </div>
                            <form class="form-horizontal" action="controller/ClientAttenteActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input class="btn-fixed-width-big" type="text" name="nom"/>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Téléphone</label>
                                        <div class="controls">
                                            <input class="btn-fixed-width-big" type="text" name="tel"/>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date</label>
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                            <input name="date" id="date" class="btn-fixed-width-big m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Bien</label>
                                        <div class="controls">
                                           <textarea class="btn-fixed-width-big" name="bien"></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Intervalle Prix</label>
                                        <div class="controls">
                                            <textarea class="btn-fixed-width-big" name="prix"></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Intervalle Supérficie</label>
                                        <div class="controls">
                                            <textarea class="btn-fixed-width-big" name="superficie"></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Emplacement Achat</label>
                                        <div class="controls">
                                            <input class="btn-fixed-width-big" type="text" name="emplacementAchat"/>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Emplacement Vente</label>
                                        <div class="controls">
                                            <input class="btn-fixed-width-big" type="text" name="emplacementVente"/>
                                        </div>  
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <input type="hidden" name="action" value="add" />
                                        <div class="controls">  
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addDemande box end -->
                        <?php 
                         if ( isset($_SESSION['clientAttente-action-message'])
                         and isset($_SESSION['clientAttente-type-message']) ) {
                             $message = $_SESSION['clientAttente-action-message'];
                             $typeMessage = $_SESSION['clientAttente-type-message']; 
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['clientAttente-action-message']);
                            unset($_SESSION['clientAttente-type-message']);
                         ?>
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Liste d'attente des clients</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <div class="btn-group pull-right">
                                        <a href="#addDemande" class="btn blue" data-toggle="modal">
                                            <i class="icon-plus-sign"></i>&nbsp;Demande Client
                                        </a>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th class="hidden-phone">Actions</th>
                                            <th>Nom</th>
                                            <th class="hidden-phone">Téléphone</th>
                                            <th class="hidden-phone">Date</th>
                                            <th class="hidden-phone">Bien</th>
                                            <th class="hidden-phone">Prix</th>
                                            <th class="hidden-phone">Supérfi</th>
                                            <th class="hidden-phone">Emp.Achat</th>
                                            <th class="hidden-phone">Emp.Vente</th>
                                            <th class="hidden-desktop hidden-tablet">Détails</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($clientAttente as $client){
                                        ?>
                                        <tr class="odd gradeX">
                                            <td class="hidden-phone">
                                                <?php
                                                if ( 
                                                    $_SESSION['userMerlaTrav']->profil() != "admin" ||  
                                                    $_SESSION['userMerlaTrav']->profil() != "manager"
                                                    ) 
                                                {
                                                ?>
                                                <a href="#update<?= $client->id() ?>" data-toggle="modal" data-id="<?= $client->id() ?>" class="btn mini green"><i class="icon-refresh"></i></a>
                                                <a href="#delete<?= $client->id() ?>" data-toggle="modal" data-id="<?= $client->id() ?>" class="btn mini red"><i class="icon-remove"></i></a>
                                                <?php  
                                                }
                                                ?>
                                            </td>    
                                            <td><?= $client->nom() ?></td>
                                            <td class="hidden-phone"><?= $client->tel() ?></td>
                                            <td class="hidden-phone"><?= date('d/m/Y', strtotime($client->date())) ?></td>
                                            <td class="hidden-phone"><?= $client->bien() ?></td>
                                            <td class="hidden-phone"><?= $client->prix() ?></td>
                                            <td class="hidden-phone"><?= $client->superficie() ?></td>
                                            <td class="hidden-phone"><?= $client->emplacementVente() ?></td>
                                            <td class="hidden-phone"><?= $client->emplacementAchat() ?></td>
                                            <td class="hidden-desktop hidden-tablet"><a class="btn blue mini" data-toggle="modal" data-id="<?= $client->id() ?>" href="#showDetails<?= $client->id() ?>"><i class="icon-info-sign"></i></a></td>
                                        </tr>
                                        <!-- showDetails box begin-->
                                        <div id="showDetails<?= $client->id()?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Informations du client <?= $client->nom() ?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Téléphone :</strong><span class="pull-right"><?= $client->tel() ?></span><br />
                                                <strong>Date :</strong><span class="pull-right"><?= date('d/m/Y', strtotime($client->date())) ?></span><br />
                                                <strong>Bien :</strong><span class="pull-right"><?= $client->bien() ?></span><br />
                                                <strong>Prix :</strong><span class="pull-right"><?= $client->prix() ?></span><br />
                                                <strong>Superficie :</strong><span class="pull-right"><?= $client->superficie() ?></span><br />
                                                <strong>Emplacement Vente :</strong><span class="pull-right"><?= $client->emplacementVente() ?></span><br />
                                                <strong>Emplacement Achat :</strong><span class="pull-right"><?= $client->emplacementAchat() ?></span><br />
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
                                        <!-- updateClient box begin-->
                                        <div id="update<?= $client->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier la demande du client </h3>
                                            </div>
                                            <form class="form-horizontal" action="controller/ClientAttenteActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir modifier la demande du client <strong><?= ucfirst($client->nom()) ?></strong> ?</p>
                                                    <div class="control-group">
                                                        <label class="control-label">Nom</label>
                                                        <div class="controls">
                                                            <input class="btn-fixed-width-big" type="text" name="nom" value="<?= $client->nom() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Téléphone</label>
                                                        <div class="controls">
                                                            <input class="btn-fixed-width-big" type="text" name="tel" value="<?= $client->tel() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Date</label>
                                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="date" id="date" class="btn-fixed-width-big m-wrap m-ctrl-small date-picker" type="text" value="<?= $client->date() ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Bien</label>
                                                        <div class="controls">
                                                            <textarea class="btn-fixed-width-big" name="bien"><?= $client->bien() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Intervalle Prix</label>
                                                        <div class="controls">
                                                            <textarea class="btn-fixed-width-big" name="prix"><?= $client->prix() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Intervalle Supérficie</label>
                                                        <div class="controls">
                                                            <textarea class="btn-fixed-width-big" name="superficie"><?= $client->superficie() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Emplacement Achat</label>
                                                        <div class="controls">
                                                            <input class="btn-fixed-width-big" type="text" name="emplacementAchat" value="<?= $client->emplacementAchat() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Emplacement Vente</label>
                                                        <div class="controls">
                                                            <input class="btn-fixed-width-big" type="text" name="emplacementVente" value="<?= $client->emplacementVente() ?>" />
                                                        </div>  
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <input type="hidden" name="idClientAttente" value="<?= $client->id() ?>" />
                                                        <input type="hidden" name="action" value="update" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateFournisseur box end -->
                                        <!-- delete box begin-->
                                        <div id="delete<?= $client->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Demande Client</h3>
                                            </div>
                                            <form class="form-horizontal loginFrm" action="controller/ClientAttenteActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer la demande du client <?= ucfirst($client->nom()) ?> ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idClientAttente" value="<?= $client->id() ?>" />
                                                        <input type="hidden" name="action" value="delete" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- delete box end -->     
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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