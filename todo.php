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
        //classes and vars
        //users number
        $users = $usersManager->getUsers();
        //$mails = $mailManager->getMails();
        
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
        $todos = $todoManager->getTodosByUser($_SESSION['userMerlaTrav']->login());
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
                            Gestion des tâches personnelles
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-check"></i>
                                <a>Liste des tâches personnelles</a>
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
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <h4><i class="icon-check"></i>Ajouter une tâche personnelle </h4>
                                <!--div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div-->
                            </div>
                            <div class="portlet-body form">
                                <!--div class="chat-form"-->
                                    <form action="controller/TodoActionController.php" method="POST" class="horizontal-form">
                                        <div class="row-fluid">
                                            <div class="span3">
                                                <div class="control-group">
                                                    <label class="control-label" for="numero">Tâche</label>
                                                    <div class="controls">
                                                        <input required="required" type="text" id="todo" name="todo" class="m-wrap">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span3">
                                                <div class="control-group">
                                                    <label class="control-label" for="numero">Date</label>
                                                    <div class="controls">
                                                        <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="date" id="date" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span3">
                                                <label class="control-label" for="numero">&nbsp;</label>
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" id="idProjet" name="idProjet" value="">
                                                <button type="submit" class="btn blue">Terminer <i class="icon-ok m-icon-white"></i></button>
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
                        <!-- BEGIN INLINE NOTIFICATIONS PORTLET-->
                        <button id="showAllTodosButton" class="get-down">Afficher tous</button>
                        <br>
                        <?php
                        foreach( $todos as $todo ) {
                            $color = "";
                            $priorityOption = "";
                            $style = "";
                            //test tasks date to show them in different colors
                            if ( $todo->date() >= date('Y-m-d') ) {
                                $color = "blue";
                            }
                            elseif ( $todo->date() < date('Y-m-d') )  {
                                $color = "red";
                            }
                            //
                            if ( $todo->date() == date('Y-m-d') ) {
                                $style = '';
                            }
                            else {
                                $style = 'style="display : none;"';
                            }
                        ?>
                        <div class="showAllTodos" <?= $style ?> >
                            <a href="include/delete-task.php?idTask=<?= $todo->id() ?>"><i class="icon-remove"></i></a>
                            <a href="#updateTodo<?= $todo->id() ?>" data-toggle="modal" data-id="<?= $todo->id() ?>" class="btn <?= $color ?> get-down delete-checkbox">
                            <?= $todo->todo() ?></a><br />
                            <!-- updateTodo box begin-->
                            <div id="updateTodo<?= $todo->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Modifier Todo </h3>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" action="controller/TodoActionController.php" method="post">
                                        <div class="control-group">
                                            <label class="control-label">Todo</label>
                                            <div class="controls">
                                                <input type="text" name="todo" value="<?= $todo->todo() ?>" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Date</label>
                                            <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                    <input name="date" id="date" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $todo->date() ?>" />
                                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <input type="hidden" name="idTodo" value="<?= $todo->id() ?>" />
                                            <input type="hidden" name="action" value="update-date" />
                                            <div class="controls">  
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <!-- updateTodo box end -->
                        </div>    
                        <?php 
                        //}//end if
                        }//end foreach
                        ?>
                        <!-- END INLINE NOTIFICATIONS PORTLET-->
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