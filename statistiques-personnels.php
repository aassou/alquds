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
    if(isset($_SESSION['userMerlaTrav']) ){
        //classes managers
        
        //classes and vars
        
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
            //admin
            $adminTasksTotalNumber = $taskManager->getTaskNumberByUser('admin')+$taskManager->getTaskDoneNumberByUser('admin');
            $adminTasksNotDoneNumber = $taskManager->getTaskNumberByUser('admin');
            $adminTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('admin');
            //laila
            $lailaTasksTotalNumber = $taskManager->getTaskNumberByUser('laila')+$taskManager->getTaskDoneNumberByUser('laila');
            $lailaTasksNotDoneNumber = $taskManager->getTaskNumberByUser('laila');
            $lailaTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('laila');
            //ikram
            $ikramTasksTotalNumber = $taskManager->getTaskNumberByUser('ikram')+$taskManager->getTaskDoneNumberByUser('ikram');
            $ikramTasksNotDoneNumber = $taskManager->getTaskNumberByUser('ikram');
            $ikramTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('ikram');
            //tijani
            $tijaniTasksTotalNumber = $taskManager->getTaskNumberByUser('tijani')+$taskManager->getTaskDoneNumberByUser('tijani');
            $tijaniTasksNotDoneNumber = $taskManager->getTaskNumberByUser('tijani');
            $tijaniTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('tijani');
            //abdelghani
            $abdelghaniTasksTotalNumber = $taskManager->getTaskNumberByUser('abdelghani')+$taskManager->getTaskDoneNumberByUser('abdelghani');
            $abdelghaniTasksNotDoneNumber = $taskManager->getTaskNumberByUser('abdelghani');
            $abdelghaniTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('abdelghani');
            //hamid
            $hamidTasksTotalNumber = $taskManager->getTaskNumberByUser('hamid')+$taskManager->getTaskDoneNumberByUser('hamid');
            $hamidTasksNotDoneNumber = $taskManager->getTaskNumberByUser('hamid');
            $hamidTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('hamid');
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
                        <h3 class="page-title">
                            Statistiques du Personnel
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-bar-chart"></i>
                                <a>Statistiques du Personnel</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <h4><i class="icon-bar-chart"></i> Statistiques du personnel de la société Groupe Annahda</h4>
                        <hr class="line">
                        <div id="container" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>  
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
    <!-- ie8 fixes -->
    <!--[if lt IE 9]>
    <script src="assets/js/excanvas.js"></script>
    <script src="assets/js/respond.js"></script>
    <![endif]-->
    <script src="assets/js/app.js"></script>        
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            App.setPage("sliders");  // set current page
            App.init();
        });
    </script>
    <!------------------------- BEGIN HIGHCHARTS  --------------------------->
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <!--script src="http://code.highcharts.com/themes/dark-unica.js"></script-->
    <script src="http://code.highcharts.com/modules/data.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    <script> 
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Statistiques des tâches du personnel'
            },
            xAxis: {
                categories: ['Rabie', 'Abdelghani', 'Hamid', 'Laila', 'Tijani', 'Ikram']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total des tâches'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: 'Tâches rélisées',
                data: [<?= $adminTasksDoneNumber ?>, <?= $abdelghaniTasksDoneNumber ?>, <?= $hamidTasksDoneNumber ?>, <?= $lailaTasksDoneNumber ?>, <?= $tijaniTasksDoneNumber ?>, <?= $ikramTasksDoneNumber ?>]
            }, {
                name: 'Tâches non réalisées',
                data: [<?= $adminTasksNotDoneNumber ?>, <?= $abdelghaniTasksNotDoneNumber ?>, <?= $hamidTasksNotDoneNumber ?>, <?= $lailaTasksNotDoneNumber ?>, <?= $tijaniTasksNotDoneNumber ?>, <?= $ikramTasksNotDoneNumber ?>]
            }]
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