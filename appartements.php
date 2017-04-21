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
    	$idProjet = 0;
    	$projetManager = new ProjetManager($pdo);
		if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
			$appartementManager = new AppartementManager($pdo);
            $contratManager = new ContratManager($pdo);
            $clientManager = new ClientManager($pdo);
			$appartements = "";
			//test the appartement object number: if exists get appartement else do nothing
			$appartementNumber = $appartementManager->getAppartementNumberByIdProjet($idProjet);
			if($appartementNumber != 0){
				/*$appartementPerPage = 10;
		        $pageNumber = ceil($appartementNumber/$appartementPerPage);
		        $p = 1;
		        if(isset($_GET['p']) and ($_GET['p']>0 and $_GET['p']<=$pageNumber)){
		            $p = $_GET['p'];
		        }
		        else{
		            $p = 1;
		        }
		        $begin = ($p - 1) * $appartementPerPage;
		        $pagination = paginate('appartements.php?idProjet='.$idProjet, '&p=', $pageNumber, $p);*/
				$appartements = $appartementManager->getAppartementByIdProjet($idProjet);	
			}
		}
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
							Gestion des Appartements - Projet : <strong><?= $projet->nom() ?></strong>   
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="dashboard.php">Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<i class="icon-briefcase"></i>
								<a href="projets.php">Gestion des projets</a>
								<i class="icon-angle-right"></i>
							</li>
							<li>
                                <a href="projet-details.php?idProjet=<?= $projet->id() ?>">Projet <strong><?= $projet->nom() ?></strong></a>
                                <i class="icon-angle-right"></i>
                            </li>
							<li><a>Gestion des appartements</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<?php if($idProjet!=0){ ?>
				<div class="row-fluid"> 
					<div class="span12">
						<div class="get-down">
						    <input style="margin-top:5px;" class="m-wrap stay-away btn-fixed-width-big" name="criteria" id="criteria" type="text" placeholder="Chercher par code, status..." />
						    <?php
                            if ( 
                                $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                $_SESSION['userMerlaTrav']->profil()=="manager" 
                            ) {
                            ?>
							<a style="margin-top:5px;" href="#addAppartement" class="btn btn-fixed-width-big green stay-away" data-toggle="modal"><i class="icon-plus-sign m-icon-white"></i>&nbsp;Nouvel Appartement</a>
							<?php
                            }
                            ?>
                            <a style="margin-top:5px;" href="controller/AppatementsListPrintController.php?idProjet=<?= $idProjet ?>" class="btn btn-fixed-width-big blue stay-away" data-toggle="modal"><i class="icon-print m-icon-white"></i> Version Imprimable</a>
						</div>
						<!-- addAppartement box begin-->
                        <div id="addAppartement" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter Nouvel Appartement</h3>
                            </div>
                            <form class="form-horizontal" action="controller/AppartementActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Code</label>
                                        <div class="controls">
                                            <input type="text" name="code" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Supérficie</label>
                                        <div class="controls">
                                            <input type="text" name="superficie" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Façade</label>
                                        <div class="controls">
                                            <input type="text" name="facade" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Nombre de pièces</label>
                                        <div class="controls">
                                            <input type="text" name="nombrePiece" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Prix</label>
                                        <div class="controls">
                                            <input type="text" name="prix" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="niveau">Niveau</label>
                                        <div class="controls">
                                            <select style="width:150px" name="niveau" class="m-wrap">
                                                <option value="RC">RC</option>
                                                <option value="Mezzanine">Mezzanine</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="status">Status</label>
                                        <div class="controls">
                                            <select style="width:150px" name="status" id="status" class="m-wrap">
                                                <option value="Disponible">Disponible</option>
                                                <option value="Réservé">Réservé</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="cave">Cave</label>
                                        <div class="controls">
                                            <select style="width:150px" name="cave" class="m-wrap">
                                                <option value="Avec">Avec</option>
                                                <option value="Sans">Sans</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group" id="par" style="display: none">
                                        <label class="control-label">Réservé par </label>
                                        <div class="controls">
                                            <input type="text" name="par" class="m-wrap">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="action" value="add" />  
                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addAppartement box end -->
						<!-- BEGIN Terrain TABLE PORTLET-->
						<?php 
						if(isset($_SESSION['appartement-action-message']) 
						and isset($_SESSION['appartement-type-message'])){ 
						      $message = $_SESSION['appartement-action-message'];
                              $typeMessage = $_SESSION['appartement-type-message'];
						?>
						    <br><br>
                         	<div class="alert alert-<?= $typeMessage ?>">
								<button class="close" data-dismiss="alert"></button>
							    <?= $message ?>
							</div>
                         <?php } 
                         	unset($_SESSION['appartement-action-message']);
                            unset($_SESSION['appartement-type-message']);
                         ?>
						<div class="portlet appartements">
							<div class="portlet-body">
							    <div class="scroller" data-height="500px" data-always-visible="1"><!-- BEGIN DIV SCROLLER -->
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th style="width: 10%">Code</th>
											<th class="hidden-phone" style="width: 5%">Niveau</th>
											<th style="width: 10%">Superficie</th>
											<th class="hidden-phone" style="width: 5%">Façade</th>
											<th class="hidden-phone" style="width: 30%">Nbr.Pièces</th>
											<th class="hidden-phone" style="width: 5%">Cave</th>
											<th style="width: 10%">Status</th>
											<th style="width: 15%"></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if($appartementNumber != 0){
										foreach($appartements as $appartement){
										?>		
										<tr class="appartements">
											<td>
												<div class="btn-group">
												    <a style="width: 50px" class="btn mini dropdown-toggle" href="#" title="Prix : <?= number_format($appartement->prix(), 2, ',', ' ') ?> DH" data-toggle="dropdown">
												    	<?= $appartement->nom() ?> 
												        <i class="icon-angle-down"></i>
												    </a>
												    <?php
                                                    if ( 
                                                        $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                        $_SESSION['userMerlaTrav']->profil()=="manager"    
                                                    ) {
                                                    ?>
												    <ul class="dropdown-menu">
												        <li>
												            <a class="hidden-desktop">
                                                                Prix: <?= number_format($appartement->prix(), 2, ',', ' ') ?> DH
                                                            </a>
                                                            <a href="#updateEtatAppartement<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
                                                               Changer État Appart
                                                            </a>
												        	<a href="appartement-detail.php?idAppartement=<?= $appartement->id() ?>&idProjet=<?= $appartement->idProjet() ?>">
																Fiche descriptif
															</a>
												        	<a href="#addPieces<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
																Ajouter Document
															</a>
												        	<a href="#updateAppartement<?= $appartement->id();?>" data-toggle="modal" data-id="<?= $appartement->id(); ?>">
																Modifier
															</a>
															<?php
															if ( $appartement->status() != "Vendu" ) {
															?>
															<a href="#deleteAppartement<?= $appartement->id();?>" data-toggle="modal" data-id="<?= $appartement->id(); ?>">
																Supprimer
															</a>
															<?php  
                                                            }
                                                            ?>
												        </li>
												    </ul>
												    <?php
                                                    }
                                                    ?>
												</div>
											</td>
											<td class="hidden-phone"><?= $appartement->niveau() ?>Et</td>
											<td><?= $appartement->superficie() ?> m<sup>2</sup></td>
											<td class="hidden-phone"><?= $appartement->facade() ?></td>
											<td class="hidden-phone"><?= $appartement->nombrePiece() ?> pièces</td>
											<td class="hidden-phone">
												<?php if($appartement->cave()=="Sans"){ ?><a class="btn mini black">Sans</a><?php } ?>
												<?php if($appartement->cave()=="Avec"){ ?><a class="btn mini purple">Avec</a><?php } ?>
											</td>
											<td>
												<?php
												if ( $appartement->status()=="Disponible" ) {
                                                    if ( 
                                                        $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                        $_SESSION['userMerlaTrav']->profil()=="manager"    
                                                    ) {    
												?>
													<a class="btn mini green" href="#changeToReserve<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
														Disponible
													</a>
												<?php 
                                                    }
                                                    else {
                                                ?>
                                                    <a class="btn mini green">
                                                        Disponible
                                                    </a>
                                                <?php        
                                                    } 
                                                }    
                                                ?>
												<?php 
												if ( $appartement->status()=="R&eacute;serv&eacute;" ) {
												     if ( 
												        $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                        $_SESSION['userMerlaTrav']->profil()=="manager"
                                                     ) {   
												?>
													<a class="btn mini red" href="#changeToDisponible<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
														Réservé
													</a>
												<?php
                                                     }
                                                     else {
                                                ?>
                                                    <a class="btn mini red">
                                                        Réservé
                                                    </a>
                                                <?php         
                                                     }
                                                } 
                                                ?>
												<?php 
												if ( $appartement->status()=="Vendu" ) { 
												?>
													<a class="btn mini blue">Vendu</a>
												<?php 
                                                } 
                                                ?>
											</td>
											<td>
												<?php
												if( $appartement->status()=="R&eacute;serv&eacute;" ){
												    if ( 
												        $_SESSION['userMerlaTrav']->profil()=="admin" || 
                                                        $_SESSION['userMerlaTrav']->profil()=="manager"    
                                                    ) {
												?>
												<a class="btn mini" title="<?= $appartement->par() ?>" href="#updateClient<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
													Pour
												</a>
												<?php
                                                    }
                                                    else{
                                                ?>
                                                <a class="btn mini" title="<?= $appartement->par() ?>">
                                                    Pour
                                                </a>    
                                                <?php        
                                                    }
												}
                                                else if( $appartement->status()=="Vendu" ){
                                                ?>
                                                    <a class="btn mini" href="#showBuyer<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>" title="<?= $clientManager->getClientById($contratManager->getIdClientByIdProjetByIdBienTypeBien($idProjet, $appartement->id(), "appartement"))->nom() ?>">
                                                        Pour
                                                    </a>
                                                <?php    
                                                }
                                                //Show appartement_status
                                                if ( 
                                                    $_SESSION['userMerlaTrav']->profil()=="admin" || 
                                                    $_SESSION['userMerlaTrav']->profil()=="manager" ) 
                                                {
                                                ?>
                                                <a class="btn mini dark-cyan" href="#statusAppartement<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">État</a>
                                                <?php    
                                                }
												?>
											</td>
										</tr>
										<?php if( $appartement->status()=="Vendu" ){ ?>
										<!-- showBuyer box begin -->
                                        <div id="showBuyer<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Appartement Pour Client</h3>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Client :</strong><span class="pull-right"><?= $clientManager->getClientById($contratManager->getIdClientByIdProjetByIdBienTypeBien($idProjet, $appartement->id(), "appartement"))->nom() ?></span><br />
                                                <strong>Prix :</strong><span class="pull-right"><?= number_format($appartement->prix(), 2, ',', ' ') ?>&nbsp;DH</span><br />
                                            </div>
                                            <div class="modal-footer">
                                                <div class="control-group">
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- showBuyer box end -->
                                        <?php } ?>
										<!-- updateEtatAppartement box begin -->
                                        <div id="updateEtatAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Changer l'état de l'appartement <strong><?= $appartement->nom() ?></strong> </h3>
                                            </div>
                                            <form class="form-inline" action="controller/AppartementActionController.php" method="post">
                                                 <div class="modal-body">
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Titre Foncière</label>
                                                            <input type="text" name="titre" value="<?= $appartement->titre() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Supérifice</label>
                                                            <input type="text" name="superficie2" value="<?= $appartement->superficie2() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Prix déclaré</label>
                                                            <input type="text" name="prixDeclare" value="<?= $appartement->prixDeclare() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Avances sur prix déclaré</label>
                                                            <input type="text" name="avancePrixDeclare" value="<?= $appartement->avancePrixDeclare() ?>" />
                                                        </div>
                                                    </div>
                                                 </div>
                                                 <div class="modal-footer">
                                                     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateEtatAppartement" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                 </div>
                                            </form>
                                        </div>  
                                        <!-- updateEtatAppartement box end -->
										<!-- statusAppartement box begin -->
										<div id="statusAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>État de l'appartement</h3>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Titre Foncière :</strong><span class="pull-right"><?= $appartement->titre() ?></span><br />
                                                <strong>Supérifice :</strong><span class="pull-right"><?= $appartement->superficie2() ?>&nbsp;m<sup>2</sup></span><br />
                                                <strong>Prix déclaré :</strong><span class="pull-right"><?= number_format($appartement->prixDeclare(), 2, ',', ' ') ?>&nbsp;DH</span><br />
                                                <strong>Avance sur prix déclaré :</strong><span class="pull-right"><?= number_format($appartement->avancePrixDeclare(), 2, ',', ' ') ?>&nbsp;DH</span><br />
                                            </div>
                                            <div class="modal-footer">
                                                <div class="control-group">
                                                    <button class="btn dark-cyan" data-dismiss="modal"aria-hidden="true">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- statusAppartement box end -->
										<!-- change to disponible box begin-->
										<div id="changeToDisponible<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Changer le status vers "Disponible"</h3>
											</div>
											<form class="form-horizontal" action="controller/AppartementActionController.php" method="post" enctype="multipart/form-data">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir changer le status de 
														<a class="btn mini red">Réservé</a> vers 
														<a class="btn mini green">Disponible</a> ?</p>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateStatus" />
                                                        <input type="hidden" name="status" value="Disponible" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- change to disponible box end -->	
										<!-- change to reserve box begin-->
										<div id="changeToReserve<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Changer le status vers "Réservé"</h3>
											</div>
											<form class="form-horizontal" action="controller/AppartementActionController.php" method="post" enctype="multipart/form-data">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir changer le status de 
														<a class="btn mini green">Disponible</a> vers 
														<a class="btn mini red">Réservé</a> ?</p>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateStatus" />
                                                        <input type="hidden" name="status" value="Réservé" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
									       </form>
										</div>
										<!-- change to reserve box end -->	
										<!-- add file box begin-->
										<div id="addPieces<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Ajouter des pièces pour cette appartement</h3>
											</div>
											<form class="form-horizontal" action="controller/AppartementActionController.php?p=1" method="post" enctype="multipart/form-data">
											     <div class="modal-body">
													<div class="control-group">
														<label class="right-label">Nom Pièce</label>
														<input type="text" name="nom" />
														<label class="right-label">Lien</label>
														<input type="file" name="url" />
													</div>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="addPieces" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- add files box end -->	
										<!-- updateAppartement box begin-->
                                        <div id="updateAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier Appartement</h3>
                                            </div>
                                            <form class="form-horizontal" action="controller/AppartementActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir modifier <strong>Appartement : <?= $appartement->nom() ?>- Niveau : <?= $appartement->niveau() ?></strong></p>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <label class="control-label">Code</label>
                                                            <input type="text" name="code" value="<?= $appartement->nom() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Supérficie</label>
                                                        <div class="controls">
                                                            <input type="text" name="superficie" value="<?= $appartement->superficie() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Façade</label>
                                                        <div class="controls">
                                                            <input type="text" name="facade" value="<?= $appartement->facade() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Nombre de pièces</label>
                                                        <div class="controls">
                                                            <input type="text" name="nombrePiece" value="<?= $appartement->nombrePiece() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Prix</label>
                                                        <div class="controls">
                                                            <input type="text" name="prix" value="<?= $appartement->prix() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="niveau">Niveau</label>
                                                        <div class="controls">
                                                            <select style="width:150px" name="niveau" class="m-wrap">
                                                                <option value="<?= $appartement->niveau() ?>"><?= $appartement->niveau() ?></option>
                                                                <option disabled="disabled">-----------------</option>
                                                                <option value="RC">RC</option>
                                                                <option value="Mezzanine">Mezzanine</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ( $appartement->status() != "Vendu" ){ 
                                                    ?>
                                                    <div class="control-group">
                                                        <label class="control-label" for="status">Status</label>
                                                        <div class="controls">
                                                            <select style="width:150px" name="status" id="status" class="m-wrap">
                                                                <option value="<?= $appartement->status() ?>"><?= $appartement->status() ?></option>
                                                                <option disabled="disabled">-----------------</option>
                                                                <option value="Disponible">Disponible</option>
                                                                <option value="Réservé">Réservé</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    } 
                                                    ?>
                                                    <div class="control-group">
                                                        <label class="control-label" for="cave">Cave</label>
                                                        <div class="controls">
                                                            <select style="width:150px" name="cave" class="m-wrap">
                                                                <option value="<?= $appartement->cave() ?>"><?= $appartement->cave() ?></option>
                                                                <option disabled="disabled">-----------------</option>
                                                                <option value="Avec">Avec</option>
                                                                <option value="Sans">Sans</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Réservé par </label>
                                                        <div class="controls">
                                                            <input type="text" name="par" class="m-wrap" value="<?= $appartement->par() ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <input type="hidden" name="action" value="update" />  
                                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                            <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateAppartement box end -->
										<!-- updateClient box begin -->
										<div id="updateClient<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Changer le client <strong><?= $appartement->par() ?></strong> </h3>
											</div>
											<form class="form-horizontal loginFrm" action="controller/AppartementActionController.php" method="post">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir changer le nom du client <strong><?= $appartement->par() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label">Réservé par</label>
														<input type="text" name="par" value="<?= $appartement->par() ?>" />
													</div>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateClient" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>	
										<!-- updateClient box end -->
										<!-- delete box begin-->
										<div id="deleteAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer Appartement <strong><?= $appartement->nom() ?></strong> </h3>
											</div>
											<form class="form-horizontal loginFrm" action="controller/AppartementActionController.php" method="post">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir supprimer cette appartement <strong><?= $appartement->nom() ?></strong> ?</p>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- delete box end -->	
										<?php
										}//end of loop
										}//end of if
										?>
									</tbody>
								</table>
							</div>
						</div>
						</div><!-- END DIV SCROLLER -->
						<!-- END Terrain TABLE PORTLET-->
					</div>
				</div>
				<?php }
				else{
				?>
				<div class="alert alert-error">
					<button class="close" data-dismiss="alert"></button>
					<strong>Erreur système : </strong>Ce projet n'existe pas sur votre système. Pour plus d'informations consulter votre administrateur.		
				</div>
				<?php
				}
				?>
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
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="assets/js/excanvas.js"></script>
	<script src="assets/js/respond.js"></script>
	<![endif]-->
	<script src="assets/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>	
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<script type="text/javascript" src="assets/js/jquery.pulsate.min.js"></script>
	<script src="assets/js/app.js"></script>
	<script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            //App.setPage("table_editable");
            App.init();
        });
        $('.appartements').show();
        $('#criteria').keyup(function(){
            $('.appartements').hide();
           var txt = $('#criteria').val();
           $('.appartements').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
        $('#status').on('change',function(){
            if( $(this).val()!=="Disponible"){
            $("#par").show()
            }
            else{
            $("#par").hide()
            }
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