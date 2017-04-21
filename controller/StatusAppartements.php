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
    if ( isset($_SESSION['userMerlaTrav']) ) {
        //classes managers  
        $projetManager = new ProjetManager($pdo);
        $appartementManager = new AppartementManager($pdo);
        $contratManager = new ContratManager($pdo);
        $clientManager = new ClientManager($pdo);
        //objs and vars
        $appartements = $appartementManager->getAppartementsNonVendu();
        $appartementsRevendre = $contratManager->getAppartementsRevendre();
ob_start();
?>
<style type="text/css">
    p, h1{
        text-align: center;
        text-decoration: underline;
    }
    table, tr, td, th {
        border-collapse: collapse;
        width:auto;
        border: 1px solid black;
        font-size: 11px;
    }
    td, th{
        padding : 5px;
    }
    
    th{
        background-color: grey;
    }
    table, a{
        text-decoration: none;
    }
</style>
<page backtop="15mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <!--img src="../assets/img/logo_company.png" style="width: 110px" /-->
    <h1>État Appartements</h1>
    <p>Imprimé le <?= date('d-m-Y') ?> | <?= date('h:i') ?> </p>
    <table>
        <tr>
            <th style="width: 5%">Code</th>
            <th style="width: 10%">Projet</th>
            <th style="width: 5%">Niv</th>
            <th style="width: 10%">Superficie</th>
            <th style="width: 10%">Façade</th>
            <th style="width: 25%">Nbr.Pièces</th>
            <th style="width: 5%">Cave</th>
            <th style="width: 10%">Status</th>
            <th style="width: 20%"></th>
        </tr>
        <?php
        foreach ( $appartements as $appartement ) {
        ?>      
        <tr>
            <td style="width: 5%"><?= $appartement->nom() ?> </td>
            <td style="width: 10%"><?= $projetManager->getProjetById($appartement->idProjet())->nom() ?></td>
            <td style="width: 5%"><?= $appartement->niveau() ?>Et</td>
            <td style="width: 10%"><?= $appartement->superficie() ?> m<sup>2</sup></td>
            <td style="width: 10%"><?= $appartement->facade() ?></td>
            <td style="width: 25%"><?= $appartement->nombrePiece() ?> pièces</td>
            <td style="width: 5%"><?= $appartement->cave() ?></td>
            <td style="width: 10%"><?= $appartement->status() ?></td>
            <td style="width: 20%"><?php if( $appartement->status()=="R&eacute;serv&eacute;" ){ echo $appartement->par(); } ?></td>
        </tr>
        <?php
        }//end of loop
        ?>
        <?php
        foreach ( $appartementsRevendre as $contrat ) {
            $appartement = $appartementManager->getAppartementById($contrat->idBien());
        ?>      
        <tr>
            <td style="width: 5%"><?= $appartement->nom() ?> </td>
            <td style="width: 10%"><?= $projetManager->getProjetById($appartement->idProjet())->nom() ?></td>
            <td style="width: 5%"><?= $appartement->niveau() ?>Et</td>
            <td style="width: 10%"><?= $appartement->superficie() ?> m<sup>2</sup></td>
            <td style="width: 10%"><?= $appartement->facade() ?></td>
            <td style="width: 25%"><?= $appartement->nombrePiece() ?> pièces</td>
            <td style="width: 5%"><?= $appartement->cave() ?></td>
            <td style="width: 10%">Revendre</td>
            <td style="width: 20%"><?= $clientManager->getClientById($contrat->idClient())->nom()." : ".number_format($appartement->montantRevente(), 2, ',', ' ')." DH" ?></td>
        </tr>
        <?php
        }//end of loop
        ?>
    </table>
    <br><br> 
    <br><br>
    <page_footer>
    <hr/>
    <p style="text-align: center;font-size: 9pt;"></p>
    </page_footer>
</page>    
<?php
    $content = ob_get_clean();
    
    require('../lib/html2pdf/html2pdf.class.php');
    try{
        $pdf = new HTML2PDF('L', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
        $fileName = "StatusAppartements-".date('Ymdhi').'.pdf';
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