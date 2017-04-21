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
        $idCaisse = $_GET['idCaisse'];
        $caisseManager = new CaisseManager($pdo);
        $caisse = $caisseManager->getCaisseById($idCaisse);
ob_start();
?>
<style type="text/css">
    p, h1, h2, h3, h4{
        text-align: center;
        text-decoration: underline;
    }
    .detailLivraison{
        text-decoration: none;
        text-align: center;
        font-size: 16px;
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
<page backtop="15mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <h4>Bon de caisse - Société Annahda</h4>
    <table>
        <tr>
            <th style="width:20%">Date Opération</th>
            <th style="width:10%">Crédit</th>
            <th style="width:10%">Débit</th>
            <th style="width:20%">Destination</th>
            <th style="width:40%">Designation</th>
        </tr>
        <tr>
            <td><?= date('d/m/Y', strtotime($caisse->dateOperation())) ?></td>
            <?php
            if ( $caisse->type() == "Entree" ) {
            ?>
            <td><?= number_format($caisse->montant(), 2, ',', ' ') ?></td>
            <td></td>
            <?php  
            }
            else {
            ?>
            <td></td>
            <td><?= number_format($caisse->montant(), 2, ',', ' ') ?></td>
            <?php
            }
            ?>
            <td><?= $caisse->destination() ?></td>
            <td><?= $caisse->designation() ?></td>
        </tr>
    </table>
    <page_footer>
    <hr/>
    <p style="text-align: center;font-size: 9pt;"></p>
    </page_footer>
</page>    
<?php
    $content = ob_get_clean();
    
    require('../lib/html2pdf/html2pdf.class.php');
    try{
        $pdf = new HTML2PDF('L', 'A5', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
        $fileName = "BonCaisse-".date('Ymdhi').'.pdf';
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