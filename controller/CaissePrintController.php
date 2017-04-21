<?php
    //classes loading begin
    function classLoad ($myClass) {
        if(file_exists('../model/'.$myClass.'.php')){
            include('../model/'.$myClass.'.php');
        }
        elseif(file_exists('../controller/'.$myClass.'.php')){
            include('../controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('../config.php');  
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
        //classes managers  
        $projetManager = new ProjetManager($pdo);
        $caisseManager = "";
        $projets = $projetManager->getProjets();
        $titre = "";
        $caisses = "";
        $titreDocument = "";
        $totalCaisse = 0;
        $societe = htmlentities($_POST['societe']);
        if ( $societe == 1 ) {
            $caisseManager = new CaisseManager($pdo);
            $titre = "Groupe Al Quds";
        }
        else if ( $societe == 2 ) {
            $caisseManager = new CaisseIaazaManager($pdo);
            $titre = "Société Iaaza";
        }
        
        $criteria = htmlentities($_POST['criteria']);
        if( $criteria=="parDate" ) {
            $debugging = "0";
            $dateFrom = htmlentities($_POST['dateFrom']);
            $dateTo = htmlentities($_POST['dateTo']); 
            $type = htmlentities($_POST['type']);
            $destination = htmlentities($_POST['destination']);
            if ( $societe == 2 ) {
                $destination = "Tout";
            }
            //We test here on type of criteria if it is IN/OR or All the entries
            if( $type == "Toutes" && $destination == "Tout" ) {
                $caisses = $caisseManager->getCaissesByDates($dateFrom, $dateTo);
                $titreDocument = "Liste des opérations entre : ".date('d/m/Y', strtotime($dateFrom)).' - '.date('d/m/Y', strtotime($dateTo));
                $totalCaisse = 
                $caisseManager->getTotalCaisseByTypeByDate('Entree', $dateFrom, $dateTo) - $caisseManager->getTotalCaisseByTypeByDate('Sortie', $dateFrom, $dateTo);
                $debugging = "1";   
            }
            else if( $type == "Toutes" && $destination != "Tout" ) {
                $caisses = $caisseManager->getCaissesByDatesByDestination($dateFrom, $dateTo, $destination);
                $titreDocument = "Liste des opérations entre : ".date('d/m/Y', strtotime($dateFrom)).' - '.date('d/m/Y', strtotime($dateTo))." - (Destination : ".$destination.")";
                $totalCaisse = 
                $caisseManager->getTotalCaisseByTypeByDateByDestination('Entree', $dateFrom, $dateTo, $destination) - $caisseManager->getTotalCaisseByTypeByDateByDestination('Sortie', $dateFrom, $dateTo, $destination);
                $debugging = "2";   
            }
            else if ( $type != "Toutes" && $destination == "Tout" ) {
                $caisses = $caisseManager->getCaissesByDatesByType($dateFrom, $dateTo, $type);
                $titreDocument = "Liste des opérations d'".$type." entre : ".date('d/m/Y', strtotime($dateFrom)).' - '.date('d/m/Y', strtotime($dateTo));
                $totalCaisse =
                $caisseManager->getTotalCaisseByTypeByDate($type, $dateFrom, $dateTo);
                //$caisseManager->getTotalCaisseByType($type, $dateFrom, $dateTo);
                $debugging = "3";
            }
            else if ( $type != "Toutes" && $destination != "Tout" ) {
                $caisses = $caisseManager->getCaissesByDatesByTypeByDestination($dateFrom, $dateTo, $type, $destination);
                $titreDocument = "Liste des opérations d'".$type." entre : ".date('d/m/Y', strtotime($dateFrom)).' - '.date('d/m/Y', strtotime($dateTo))." - (".$destination.")";
                $totalCaisse =
                $caisseManager->getTotalCaisseByTypeByDateByDestination($type, $dateFrom, $dateTo, $destination);
                //$caisseManager->getTotalCaisseByType($type, $dateFrom, $dateTo);
                $debugging = "4";
            }
        }
        else if ( $criteria=="toutesCaisse" ) {
            $caisses = $caisseManager->getCaisses();
            $titreDocument = "Bilan de toutes les opérations de caisse";
            $totalCaisse = $caisseManager->getTotalCaisseByType('Entree') - $caisseManager->getTotalCaisseByType('Sortie');   
            /*if ( isset($_POST['type']) ) {
                $type = htmlentities($_POST['type']);
                $caisses = $caisseManager->getCaissesByType($type);
                $titreDocument = "Liste des opérations de type : ".$type;
            }*/
            $debugging = "5";
        }

ob_start();
?>
<style type="text/css">
    p, h1{
        text-align: center;
        text-decoration: underline;
        font-size: 16px;
    }
    h2{
        text-align: center;
        text-decoration: underline;
        font-size: 11px;
    }
    table {
            border-collapse: collapse;
            width:100%;
        }
        
        table, th, td {
            border: 1px solid black;
        }
        td, th{
            padding : 5px;
        }
        
        th{
            background-color: grey;
        }
</style>
<page backtop="5mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <!--img src="../assets/img/logo_company.png" style="width: 110px" /-->
    <h1><?= $titre ?></h1>
    <h1><?= $titreDocument ?></h1>
    <h2>Imprimé le <?= date('d-m-Y') ?> | <?= date('h:i') ?> </h2>
    <br>
    <table>
        <tr>
            <!--th style="width: 20%">Type</th-->
            <th style="width: 15%">Date Opé</th>
            <th style="width: 15%">Crédit</th>
            <th style="width: 15%">Débit</th>
            <th style="width: 35%">Désignation</th>
            <th style="width: 20%">Destination</th>
        </tr>
        <?php
        foreach($caisses as $caisse){
        ?>      
        <tr>
            <td style="width: 15%"><?= date('d/m/Y', strtotime($caisse->dateOperation())) ?></td>
            <?php
            if ( $caisse->type() == "Entree" ) {
            ?>
            <td style="width: 15%"><?= number_format($caisse->montant(), 2, ',', ' ') ?></td>
            <td style="width: 15%"></td>
            <?php  
            }
            else {
            ?>
            <td style="width: 15%"></td>
            <td style="width: 15%"><?= number_format($caisse->montant(), 2, ',', ' ') ?></td>
            <?php
            }
            ?>
            <td style="width: 35%"><?= $caisse->designation() ?></td>
            <td style="width: 20%"><?= $caisse->destination() ?></td>
        </tr>   
        <?php
        }//end of loop
        ?>
    </table>
    <table>
        <tr>
            <th style="width: 15%">Solde</th>
            <td style="width: 30%; text-align: center"><strong><?= number_format($totalCaisse, 2, ',', ' ') ?>&nbsp;DH</strong></td>
            <th style="width: 55%"></th>
        </tr>
    </table>
    <br><br>
    <page_footer>
    <hr/>
    <p style="text-align: center;font-size: 9pt;">STE GROUPE Al Quds SARL – Quartier xxx, Nador. 
        <br>Tél : 05 36 xx xx xx - Fax : 05 36 xx xx xx </p>
    </page_footer>
</page>    
<?php
    $content = ob_get_clean();
    
    require('../lib/html2pdf/html2pdf.class.php');
    try{
        $pdf = new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
        $fileName = "BilanCaisse-".date('Ymdhi').'.pdf';
        $pdf->Output($fileName);
    }
    catch(HTML2PDF_exception $e){
        die($e->getMessage());
    }
}
else{
    header("Location:index.php");
}
?>