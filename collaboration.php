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
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
        $showTodos = 0;
        if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
            $showTodos = 1;    
        }
        //classes managers
        $usersManager = new UserManager($pdo);
        $mailManager = new MailManager($pdo);
        $collaborationManager = new CollaborationManager($pdo);
        //classes and vars
        //users number
        $users = $usersManager->getUsers();
        //$mails = $mailManager->getMails();
        $ideas = $collaborationManager->getCollaborations();
        //$ideas = $collaborationManager->getCollaborationsNonValidees();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8" />
    <title>ImmoERP - Management Application</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/metro.css" rel="stylesheet" />
    <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/style_responsive.css" rel="stylesheet" />
    <link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
    <link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
    <link rel="stylesheet" type="text/css" href="assets/gritter/css/jquery.gritter.css" />
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <?php 
        include("include/top-menu.php"); 
        ?>   
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
                            Collaboration et développement
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-edit"></i>
                                <a>Collaboration et développement</a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <!-- BEGIN PORTLET-->
                <div class="row-fluid">
                    <div class="span12">
                        <?php 
                         if ( isset($_SESSION['collaboration-action-message'])
                         and isset($_SESSION['collaboration-type-message']) ) {
                             $message = $_SESSION['collaboration-action-message'];
                             $typeMessage = $_SESSION['collaboration-type-message']; 
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['collaboration-action-message']);
                            unset($_SESSION['collaboration-type-message']);
                         ?>
                        <div class="portlet box grey">
                            <div class="portlet-title">
                                <h4><i class="icon-edit"></i>Nouvelle idée à développer </h4>
                                <!--div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div-->
                            </div>
                            <div class="portlet-body form">
                                <!--div class="chat-form"-->
                                    <form action="controller/CollaborationActionController.php" method="POST" class="horizontal-form">
                                        <div class="row-fluid">
                                            <div class="span3">
                                                <div class="control-group">
                                                    <label class="control-label" for="numero">Titre</label>
                                                    <div class="controls">
                                                        <input required="required" type="text" id="titre" name="titre" class="m-wrap">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" for="numero">Description</label>
                                                    <div class="controls">
                                                        <textarea rows="5" style="width:400px" required="required" type="text" id="descripton" name="description" class="m-wrap"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span3">
                                                <label class="control-label">&nbsp;</label>
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="status" value="X">
                                                <input type="hidden" name="duree" value="Non définie">
                                                <button type="submit" class="btn black">Enregistrer <i class="icon-save m-icon-white"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                <!--/div-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box grey">
                            <div class="portlet-title">
                                <h4>Liste des idées à développer</h4>
                                <div class="tools">
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <!--th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th-->
                                            <th style="width:10%">Actions</th>
                                            <th style="width:20%">Titre</th>
                                            <th style="width:50%">Description</th>
                                            <th style="width:10%">Durée</th>
                                            <th style="width:10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($ideas as $idea){
                                            $status = $idea->status()=="V"?"green":"red";
                                        ?>
                                        <tr class="odd gradeX">
                                            <!--td><input type="checkbox" class="checkboxes" value="1" /></td-->
                                            <td style="width:10%">
                                                <a href="#update<?= $idea->id() ?>" data-toggle="modal" data-id="<?= $idea->id() ?>" class="btn mini green"><i class="icon-refresh"></i></a>
                                                <!--a href="#delete<?php //$idea->id() ?>" data-toggle="modal" data-id="<?php //$idea->id() ?>" class="btn mini red"><i class="icon-remove"></i></a-->
                                            </td>    
                                            <td style="width:20%"><?= $idea->titre() ?></td>
                                            <td style="width:50%"><?= $idea->description() ?></td>
                                            <td style="width:10%"><?= $idea->duree() ?></td>
                                            <td style="width:10%"><a class="btn <?= $status ?>"><?= $idea->status() ?></a></td>
                                        </tr>
                                        <!-- update box begin-->
                                        <div id="update<?= $idea->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier les informations Idée </h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="controller/CollaborationActionController.php" method="post">
                                                    <p>Êtes-vous sûr de vouloir modifier les infos de cette idée <strong><?= $idea->titre() ?></strong> ?</p>
                                                    <div class="control-group">
                                                        <label class="control-label">Titre</label>
                                                        <div class="controls">
                                                            <input type="text" name="titre" value="<?= $idea->titre() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Description</label>
                                                        <div class="controls">
                                                            <textarea rows="5" style="width: 400px" name="description"><?= $idea->description() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="duree">Durée</label>
                                                        <div class="controls">
                                                            <input type="text" id="duree" name="duree" class="m-wrap" value="<?= $idea->duree() ?>">
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="status">Status</label>
                                                        <div class="controls">
                                                            <select name="status">
                                                                <option value="<?= $idea->status() ?>"><?= $idea->status() ?></option>
                                                                <option disabled="disabled">-------------</option>
                                                                <option value="V">V</option>
                                                                <option value="X">X</option>
                                                            </select>   
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <input type="hidden" name="idCollaboration" value="<?= $idea->id() ?>" />
                                                        <input type="hidden" name="action" value="update" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- update box end -->
                                        <!-- delete box begin-->
                                        <div id="delete<?= $idea->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Idée</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal loginFrm" action="controller/CollaborationActionController.php" method="post">
                                                    <p>Êtes-vous sûr de vouloir supprimer cette idée <?= $idea->titre() ?> ?</p>
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idCollaboration" value="<?= $idea->id() ?>" />
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
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <!-- END PORTLET-->
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
    <script src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>  
    <script src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.blockui.js"></script>
    <script src="assets/js/jquery.cookie.js"></script>
    <script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>    
    <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
    <script src="assets/jquery-knob/js/jquery.knob.js"></script>
    <script src="assets/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="assets/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/jquery.pulsate.min.js"></script>
    <script type="text/javascript" src="assets/js/notify.js"></script>
    <script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
    <!-- ie8 fixes -->
    <!--[if lt IE 9]>
    <script src="assets/js/excanvas.js"></script>
    <script src="assets/js/respond.js"></script>
    <![endif]-->
    <script src="assets/js/app.js"></script>        
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            App.setPage("table_managed");  // set current page
            App.init();
        });
        $(document).ready(function() {
            $('#showAllTodosButton').on('click', function() {
                $(".showAllTodos").toggle("show");
            });
        });
    </script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>