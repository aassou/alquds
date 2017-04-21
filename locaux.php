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
    if(isset($_SESSION['userMerlaTrav']) ){
    	//les sources
    	$idProjet = 0;
    	$projetManager = new ProjetManager($pdo);
		if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
			$locauxManager = new LocauxManager($pdo);
            $contratManager = new ContratManager($pdo);
            $clientManager = new ClientManager($pdo);
			$locaux = "";
			//test the locaux object number: if exists get locaux else do nothing
			$locauxNumber = $locauxManager->getLocauxNumberByIdProjet($idProjet);
			if($locauxNumber != 0){
				$locaux = $locauxManager->getLocauxByIdProjet($idProjet);	
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
							Gestion des Locaux Commerciaux Projet : <strong><?= $projet->nom() ?></strong> 
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
                                <a href="projet-details.php?idProjet=<?= $idProjet ?>">Projet <strong><?= $projet->nom() ?></strong></a>
                                <i class="icon-angle-right"></i>
                            </li>
							<li><a>Gestion des locaux commerciaux</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<?php if($idProjet!=0){ ?>
				<div class="row-fluid"> 
					<div class="span12">
					    <?php
                        if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {
                        ?>
						<div class="get-down">
						    <input style="margin-top:5px;" class="m-wrap stay-away btn-fixed-width-big" name="criteria" id="criteria" type="text" placeholder="Chercher par code, status..." />
                            <a style="margin-top:5px;" href="#addLocaux" class="btn icn-only green stay-away btn-fixed-width-big" data-toggle="modal"><i class="icon-plus-sign m-icon-white"></i>&nbsp;Nouveau Local</a>
                            <a style="margin-top:5px;" href="controller/LocauxListPrintController.php?idProjet=<?= $idProjet ?>" class="btn icn-only blue stay-away btn-fixed-width-big" data-toggle="modal"><i class="icon-print m-icon-white"></i>&nbsp;Version Imprimable</a>
                        </div>
                        <?php
                        }
                        ?>
                        <!-- addLocaux box begin-->
                        <div id="addLocaux" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter Nouvel Local Commercial</h3>
                            </div>
                            <form class="form-horizontal" action="controller/LocauxActionController.php" method="post">
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
                                        <label class="control-label">Prix</label>
                                        <div class="controls">
                                            <input type="text" name="prix" />
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
                                        <label class="control-label" for="mezzanine">Mezzanine</label>
                                        <div class="controls">
                                            <select style="width:150px" name="mezzanine" class="m-wrap">
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
                        <!-- addLocaux box end -->
						<!-- BEGIN Terrain TABLE PORTLET-->
						<?php if(isset($_SESSION['locaux-action-message']) 
						and isset($_SESSION['locaux-type-message'])){ 
						              $message = $_SESSION['locaux-action-message'];
                                      $typeMessage = $_SESSION['locaux-type-message'];
						?>
						    <br><br>
                         	<div class="alert alert-<?= $typeMessage ?>">
								<button class="close" data-dismiss="alert"></button>
								<?= $message ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['locaux-action-message']);
                            unset($_SESSION['locaux-type-message']);
                         ?>
						<div class="portlet">
							<div class="portlet-body">
							    <div class="scroller" data-height="600px" data-always-visible="1"><!-- BEGIN DIV SCROLLER -->
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th>Code</th>
											<th>Superficie</th>
											<th class="hidden-phone">Façade</th>
											<th class="hidden-phone">Mezzanine</th>
											<th>Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if($locauxNumber != 0){
										foreach($locaux as $locau){
										?>		
										<tr class="locaux">
											<td>
												<div class="btn-group">
												    <a style="width: 50px" class="btn mini dropdown-toggle" href="#" title="Prix : <?= number_format($locau->prix(), 2, ',', ' ') ?> DH" data-toggle="dropdown">
												    	<?= $locau->nom() ?> 
												        <i class="icon-angle-down"></i>
												    </a>
												    <?php
												    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {    
												    ?>
												    <ul class="dropdown-menu">
												        <li>
												            <a class="hidden-desktop">Prix: <?= number_format($locau->prix(), 2, ',', ' ') ?> DH</a>
												            <a href="#updateEtatLocaux<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
                                                               Changer État Local
                                                            </a>
												        	<a href="locaux-detail.php?idLocaux=<?= $locau->id() ?>&idProjet=<?= $locau->idProjet() ?>">
																Fiche descriptif
															</a>
												        	<a href="#addPieces<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
																Ajouter Document
															</a>
												        	<a href="#updateLocaux<?= $locau->id();?>" data-toggle="modal" data-id="<?= $locau->id(); ?>">
																Modifier
															</a>
															<a href="#deleteLocaux<?= $locau->id();?>" data-toggle="modal" data-id="<?= $locau->id(); ?>">
																Supprimer
															</a>
												        </li>
												    </ul>
												    <?php
                                                    }    
                                                    ?>
												</div>
											</td>
											<td><?= $locau->superficie() ?>m<sup>2</sup></td>
											<td class="hidden-phone"><?= $locau->facade() ?></td>
											<td class="hidden-phone">
												<?php if($locau->mezzanine()=="Sans"){ ?><a class="btn mini black"><?= $locau->mezzanine() ?></a><?php } ?>
												<?php if($locau->mezzanine()=="Avec"){ ?><a class="btn mini blue"><?= $locau->mezzanine() ?></a><?php } ?>
											</td>
											<td>
												<?php
												if($locau->status()=="R&eacute;serv&eacute;"){ 
												    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {    
												?>
													<a class="btn mini red" href="#changeToDisponible<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
														Réservé
													</a>
												<?php 
                                                    }
                                                    else{
                                                ?>
                                                    <a class="btn mini red" >Réservé</a>
                                                <?php        
                                                    }
                                                } 
                                                ?>
												<?php 
												if($locau->status()=="Disponible"){ 
												    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {  
												?>
													<a class="btn mini green" href="#changeToReserve<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
														Disponible
													</a>
												<?php 
                                                    }
                                                    else{
                                                ?>
                                                    <a class="btn mini green">Disponible</a>
                                                <?php
                                                    }
                                                }         
                                                ?>
												<?php if($locau->status()=="Vendu"){ ?>
													<a class="btn mini blue">Vendu</a>
												<?php } ?>
											</td>
											<td>
												<?php
												if( $locau->status() == "R&eacute;serv&eacute;" ){
												    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {  
												?>
												<a class="btn mini" title="<?= $locau->par() ?>" href="#updateClient<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
													Pour
												</a>
												<?php
                                                    }
                                                    else{
                                                ?>
                                                    <a class="btn mini" title="<?= $locau->par() ?>">
                                                        Pour
                                                    </a>    
                                                <?php        
                                                    }
												}
                                                elseif( $locau->status() == "Vendu" ){
                                                ?>
                                                <a href="#showBuyer<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>" class="btn mini" title="<?= $clientManager->getClientById($contratManager->getIdClientByIdProjetByIdBienTypeBien($idProjet, $locau->id(), "localCommercial"))->nom() ?>">
                                                    Pour 
                                                </a>
                                                <?php
                                                }
												//Show locaux_status
                                                if ( 
                                                    $_SESSION['userMerlaTrav']->profil()=="admin" || 
                                                    $_SESSION['userMerlaTrav']->profil()=="manager" ) 
                                                {
                                                ?>
                                                <a class="btn mini dark-cyan" href="#statusLocaux<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">État</a>
                                                <?php    
                                                }
                                                ?>
											</td>
										</tr>
										<!-- showBuyer box begin -->
                                        <div id="showBuyer<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Local Pour Client</h3>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Client :</strong><span class="pull-right"><?= $clientManager->getClientById($contratManager->getIdClientByIdProjetByIdBienTypeBien($idProjet, $locau->id(), "localCommercial"))->nom() ?></span><br />
                                                <strong>Prix :</strong><span class="pull-right"><?= number_format($locau->prix(), 2, ',', ' ') ?>&nbsp;DH</span><br />
                                            </div>
                                            <div class="modal-footer">
                                                <div class="control-group">
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- showBuyer box end -->
										<!-- updateEtatLocaux box begin -->
                                        <div id="updateEtatLocaux<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Changer l'état du local commercial <strong><?= $locau->nom() ?></strong> </h3>
                                            </div>
                                            <form class="form-inline" action="controller/LocauxActionController.php" method="post">
                                                 <div class="modal-body">
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Titre Foncière</label>
                                                            <input type="text" name="titre" value="<?= $locau->titre() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Supérifice</label>
                                                            <input type="text" name="superficie2" value="<?= $locau->superficie2() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Prix déclaré</label>
                                                            <input type="text" name="prixDeclare" value="<?= $locau->prixDeclare() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">    
                                                            <label class="right-label">Avances sur prix déclaré</label>
                                                            <input type="text" name="avancePrixDeclare" value="<?= $locau->avancePrixDeclare() ?>" />
                                                        </div>
                                                    </div>
                                                 </div>
                                                 <div class="modal-footer">
                                                     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateEtatLocaux" />
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                 </div>
                                            </form>
                                        </div>  
                                        <!-- updateEtatLocaux box end -->
                                        <!-- statusLocaux box begin -->
                                        <div id="statusLocaux<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>État du local commercial</h3>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Titre Foncière :</strong><span class="pull-right"><?= $locau->titre() ?></span><br />
                                                <strong>Supérifice :</strong><span class="pull-right"><?= $locau->superficie2() ?>&nbsp;m<sup>2</sup></span><br />
                                                <strong>Prix déclaré :</strong><span class="pull-right"><?= number_format($locau->prixDeclare(), 2, ',', ' ') ?>&nbsp;DH</span><br />
                                                <strong>Avance sur prix déclaré :</strong><span class="pull-right"><?= number_format($locau->avancePrixDeclare(), 2, ',', ' ') ?>&nbsp;DH</span><br />
                                            </div>
                                            <div class="modal-footer">
                                                <div class="control-group">
                                                    <button class="btn dark-cyan" data-dismiss="modal"aria-hidden="true">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- statusLocaux box end -->
										<!-- updateClient box begin -->
										<div id="updateClient<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Changer le client <strong><?= $locau->par() ?></strong> </h3>
											</div>
											<form class="form-horizontal loginFrm" action="controller/LocauxActionController.php" method="post">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir changer le nom du client <strong><?= $locau->par() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label">Réservé par</label>
														<input type="text" name="par" value="<?= $locau->par() ?>" />
													</div>
												 </div>
												 <div class="modal-foter">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateClient" />                                                      
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>	
										<!-- updateClient box end -->
										<!-- change to disponible box begin-->
										<div id="changeToDisponible<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Changer le status vers "Disponible"</h3>
											</div>
											<form class="form-horizontal" action="controller/LocauxActionController.php" method="post">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir changer le status de 
														<a class="btn mini red">Réservé</a> vers 
														<a class="btn mini green">Disponible</a> ?</p>
												 </div>
												 <div class="modal-footer">   
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateStatus" />
                                                        <input type="hidden" name="status" value="Disponible" />
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- change to disponible box end -->	
										<!-- change to reserve box begin-->
										<div id="changeToReserve<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Changer le status vers "Réservé"</h3>
											</div>
											<form class="form-horizontal" action="controller/LocauxActionController.php" method="post">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir changer le status de 
														<a class="btn mini green">Disponible</a> vers 
														<a class="btn mini red">Réservé</a> ?</p>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="updateStatus" />
                                                        <input type="hidden" name="status" value="Réservé" />
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- change to reserve box end -->	
										<!-- add file box begin-->
										<div id="addPieces<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Ajouter des pièces pour ce local</h3>
											</div>
											<form class="form-horizontal" action="controller/LocauxPiecesAddController.php" method="post" enctype="multipart/form-data">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir ajouter des pièces pour ce local ?</p>
													<div class="control-group">
														<label class="right-label">Nom Pièce</label>
														<input type="text" name="nom" />
														<label class="right-label">Lien</label>
														<input type="file" name="url" />
													</div>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <label class="right-label"></label>
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- add files box end -->	
										<!-- update box begin-->
										<div id="updateLocaux<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Modifier Infos Local commercial</h3>
											</div>
											<form class="form-horizontal" action="controller/LocauxActionController.php" method="post">
											     <div class="modal-body">
													<p>Êtes-vous sûr de vouloir modifier les informations du local <strong><?= $locau->nom() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label">Code</label>
														<div class="controls">
															<input type="text" name="code" value="<?= $locau->nom() ?>" />
														</div>
													</div>
													<div class="control-group">
														<label class="right-label">Superficie</label>
														<div class="controls">
															<input type="text" name="superficie" value="<?= $locau->superficie() ?>" />
														</div>
													</div>
													<div class="control-group">
														<label class="right-label">Façade</label>
														<div class="controls">
															<input type="text" name="facade" value="<?= $locau->facade() ?>" />
														</div>
													</div>
													<div class="control-group">
														<label class="right-label">Prix</label>
														<div class="controls">
															<input type="text" name="prix" value="<?= $locau->prix() ?>" />
														</div>
													</div>
													<div class="control-group">
														<label class="right-label">Status</label>
														<div class="controls">
															<select name="status" class="m-wrap">
															    <option value="<?= $locau->status() ?>"><?= $locau->status() ?></option>
															    <option disabled="disabled">------------</option>
																<option value="Disponible">Disponible</option>
	                                             				<option value="Réservé">Réservé</option>
															</select>
														</div>
													</div>
													<div class="control-group">
														<div class="controls">
															<label class="right-label">Mezzanine</label>
															<select name="mezzanine" class="m-wrap">
															    <option value="<?= $locau->mezzanine() ?>"><?= $locau->mezzanine() ?></option>
															    <option disabled="disabled">------------</option>
																<option value="Sans">Sans</option>
	                                             				<option value="Avec">Avec</option>
															</select>
														</div>
													</div>
													<div class="control-group">
                                                        <label class="right-label">Réservé Par</label>
                                                        <div class="controls">
                                                            <input type="text" name="par" value="<?= $locau->par() ?>" />
                                                        </div>
                                                    </div>
												 </div>
												 <div class="modal-footer">
												     <div class="control-group">
                                                        <input type="hidden" name="action" value="update" />
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
											     </div>
											</form>
										</div>
										<!-- update box end -->
										<!-- delete box begin-->
                                        <div id="deleteLocaux<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Local Commercial <strong><?= $locau->nom() ?></strong> </h3>
                                            </div>
                                            <form class="form-horizontal loginFrm" action="controller/LocauxActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer ce local <strong><?= $locau->nom() ?></strong> ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
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
	<script src="assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			//App.setPage("table_editable");
			App.init();
		});
		$('.locaux').show();
        $('#criteria').keyup(function(){
            $('.locaux').hide();
           var txt = $('#criteria').val();
           $('.locaux').each(function(){
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
else{
    header('Location:index.php');    
}
?>