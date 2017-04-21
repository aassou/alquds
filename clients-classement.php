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
        $clientsClassementManager = new ClientClassementManager($pdo);
        $clients = $clientsClassementManager->getClientClassements();
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
                            Classement des clients
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-star-empty"></i>
                                <a>Classement des clients</a>
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
                         <?php 
                         if ( isset($_SESSION['clientClassement-action-message'])
                         and isset($_SESSION['clientClassement-type-message']) ) {
                             $message = $_SESSION['clientClassement-action-message'];
                             $typeMessage = $_SESSION['clientClassement-type-message']; 
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['clientClassement-action-message']);
                            unset($_SESSION['clientClassement-type-message']);
                         ?>
                         <!--  addClassementClient box begin-->
                        <div id="addClassementClient" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Nouveau Classement Client </h3>
                            </div>
                            <form class="form-horizontal" action="controller/ClientClassementActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group autocomplet_container">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input required="required" type="text" id="nomClient" name="nom" class="m-wrap" onkeyup="autocompletClient()">
                                            <ul id="clientList"></ul>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Remarque</label>
                                        <div class="controls">
                                            <textarea name="remarque"></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Classement</label>
                                        <div class="controls">
                                            <select name="classement">
                                                <option value="1">Sérieux</option>
                                                <option value="0">Normal</option>
                                                <option value="-1">Litigieux</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <input type="hidden" name="action" value="add" />
                                        <input type="hidden" name="source" value="clients-classement" />
                                        <div class="controls">  
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addClassementClient box end -->
                         <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Classement Clients</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                    <a href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <div class="btn-group">
                                        <a class="btn green btn-fixed-width-big" href="#addClassementClient" data-toggle="modal">
                                            <i class="icon-plus-sign"></i>&nbsp;Classement Client
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a target="_blank" class="btn blue btn-fixed-width-big" href="controller/ClientClassementPrintController.php">
                                            <i class="icon-print"></i>&nbsp;Imprimer List
                                        </a>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th class="hidden-phone">Actions</th>
                                            <th>Nom</th>
                                            <th>Remarque</th>
                                            <th>Classement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($clients as $client){
                                            $classement = "";
                                            $btnClassement = "";
                                            if ( $client->classement() == 1 ) {
                                                $classement = "Sérieux";
                                                $btnClassement = "btn mini green";    
                                            }
                                            else if ( $client->classement() == 0 ) {
                                                $classement = "Normal";
                                                $btnClassement = "btn mini yellow";    
                                            }
                                            else if ( $client->classement() == -1 ) {
                                                $classement = "Litigieux";    
                                                $btnClassement = "btn mini red";
                                            }
                                        ?>
                                        <tr class="odd gradeX">
                                            <td class="hidden-phone">
                                                <a class="btn mini green" href="#updateClassementClient<?= $client->id();?>" data-toggle="modal" data-id="<? $client->id(); ?>">
                                                    <i class="icon-refresh "></i>
                                                </a>
                                                <a class="btn mini red" href="#deleteClassementClient<?= $client->id();?>" data-toggle="modal" data-id="<? $client->id(); ?>">
                                                    <i class="icon-remove "></i>
                                                </a>
                                            </td>
                                            <td><?= $client->nom() ?></td>
                                            <td><?= $client->remarque() ?></td>
                                            <td><a class="<?= $btnClassement ?>"><?= $classement ?></a></td>
                                        </tr>
                                        <!-- updateClassementClient box begin-->
                                        <div id="updateClassementClient<?= $client->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier Classement Client </h3>
                                            </div>
                                            <form class="form-horizontal" action="controller/ClientClassementActionController.php" method="post">
                                                <div class="modal-body">
                                                    <div class="control-group">
                                                        <label class="control-label">Nom</label>
                                                        <div class="controls">
                                                            <input disabled="disabled" type="text" name="nom" value="<?= $client->nom() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Remarque</label>
                                                        <div class="controls">
                                                            <textarea name="remarque"><?= $client->remarque() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Classement</label>
                                                        <div class="controls">
                                                            <select name="classement">
                                                                <option value="<?= $client->classement() ?>"><?= $classement ?></option>
                                                                <option disabled="disabled">---------------------------------</option>
                                                                <option value="1">Sérieux</option>
                                                                <option value="0">Normal</option>
                                                                <option value="-1">Litigieux</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <input type="hidden" name="idClientClassement" value="<?= $client->id() ?>" />
                                                        <input type="hidden" name="action" value="update" />
                                                        <input type="hidden" name="source" value="clients-classement" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateClassementClient box end -->
                                        <!-- deleteClassementClient box begin-->
                                        <div id="deleteClassementClient<?= $client->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Classement Client</h3>
                                            </div>
                                            <form class="form-horizontal loginFrm" action="controller/ClientClassementActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer le classement de <?= $client->nom() ?> ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idClientClassement" value="<?= $client->id() ?>" />
                                                        <input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="source" value="clients-classement" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- deleteClassementClient box end -->       
                                        <?php 
                                        }
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
    <!-- ie8 fixes -->
    <!--[if lt IE 9]>
    <script src="assets/js/excanvas.js"></script>
    <script src="assets/js/respond.js"></script>
    <![endif]-->    
    <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
    <script src="assets/js/app.js"></script>
    <script type="text/javascript" src="script.js"></script>        
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
/*else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
    header('Location:dashboard.php');
}*/
else{
    header('Location:index.php');    
}
?>