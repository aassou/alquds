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
        $fournisseurManager = new FournisseurManager($pdo);
        $employesManager = new EmployeManager($pdo);
        $annuaireManager = new AnnuaireManager($pdo);
        
        $fournisseurs = $fournisseurManager->getFournisseurs();
        $employes = $employesManager->getEmployes();
        $annuaires = $annuaireManager->getAnnuaires(); 
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
                            Annuaire
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-phone-sign"></i>
                                <a href="configuration.php">Annuaire</a>
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
                         if ( isset($_SESSION['annuaire-action-message'])
                         and isset($_SESSION['annuaire-type-message']) ) {
                             $message = $_SESSION['annuaire-action-message'];
                             $typeMessage = $_SESSION['annuaire-type-message']; 
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['annuaire-action-message']);
                            unset($_SESSION['annuaire-type-message']);
                         ?>
                        <!-- addAnnuaire box begin -->
                        <div id="addAnnuaire" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter un nouveau numéro</h3>
                            </div>
                            <form class="form-horizontal" action="controller/AnnuaireActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input type="text" name="nom" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Téléphone</label>
                                        <div class="controls">
                                            <input type="text" name="telephone1" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Description</label>
                                        <div class="controls">
                                            <textarea name="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <input type="hidden" name="action" value="add" />
                                        <input type="hidden" name="source" value="annuaire" />
                                        <div class="controls">  
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addAnnuaire box end -->
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <h4>Annuaire Téléphonique</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <div class="btn-group pull-left">
                                        <a class="btn green" href="#addAnnuaire" data-toggle="modal">
                                            <i class="icon-plus-sign"></i>Téléphone
                                        </a>
                                    </div>
                                    <div class="btn-group pull-right">
                                        <input style="width:180px;" class="m-wrap" type="text" id="criteria" placeholder="Chercher.."/>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th class="hidden-phone" style="background-color:grey">Actions</th>
                                            <th style="background-color:grey">Nom</th>
                                            <th style="background-color:grey">Téléphone</th>
                                        </tr>
                                        <?php
                                        foreach($annuaires as $annuaire){
                                        ?>
                                        <tr class="odd gradeX annuaire">
                                            <td class="hidden-phone"><a class="btn mini green" href="#updateAnnuaire<?= $annuaire->id() ?>" data-toggle="modal" data-id="<?= $annuaire->id() ?>"><i class="icon-refresh"></i></a></td>
                                            <td><?= $annuaire->nom().": ".$annuaire->description() ?></td>
                                            <td><?= $annuaire->telephone1() ?></td>
                                        </tr>     
                                        <!-- updateAnnuaire box begin -->
                                        <div id="updateAnnuaire<?= $annuaire->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier numéro</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="controller/AnnuaireActionController.php" method="post">
                                                    <div class="control-group">
                                                        <label class="control-label">Nom</label>
                                                        <div class="controls">
                                                            <input type="text" name="nom" value="<?= $annuaire->nom() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Téléphone</label>
                                                        <div class="controls">
                                                            <input type="text" name="telephone1" value="<?= $annuaire->telephone1() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Description</label>
                                                        <div class="controls">
                                                            <textarea name="description"><?= $annuaire->description() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <input type="hidden" name="action" value="update" />
                                                        <input type="hidden" name="source" value="annuaire" />
                                                        <input type="hidden" name="idAnnuaire" value="<?= $annuaire->id() ?>" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- updateAnnuaire box end -->
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <th class="hidden-phone" style="background-color:grey"></th>
                                            <th style="background-color:grey">Liste des Fournisseurs</th>
                                            <th style="background-color:grey">Téléphone</th>
                                        </tr>
                                        <?php
                                        foreach($fournisseurs as $fournisseur){
                                        ?>
                                        <tr class="odd gradeX annuaire">
                                            <td class="hidden-phone" style="width:10%"></td>
                                            <td style="width:50%"><?= $fournisseur->nom() ?></td>
                                            <td style="width:40%"><?= $fournisseur->telephone1() ?></td>
                                        </tr>     
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <th class="hidden-phone" style="background-color:grey"></th>
                                            <th style="background-color:grey">Liste des Employé</th>
                                            <th style="background-color:grey">Téléphone</th>
                                        </tr>
                                        <?php
                                        foreach($employes as $employe){
                                        ?>
                                        <tr class="odd gradeX annuaire">
                                            <td class="hidden-phone"></td>
                                            <td><?= $employe->nom() ?></td>
                                            <td><?= $employe->telephone() ?></td>
                                        </tr>     
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
        $('.annuaire').show();
        $('#criteria').keyup(function(){
            $('.annuaire').hide();
           var txt = $('#criteria').val();
           $('.annuaire').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
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