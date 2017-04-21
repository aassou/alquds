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
        $syndiqueManager = new SyndiqueManager($pdo);
        $clientManager = new ClientManager($pdo);
        //obj and vars
        $idSyndique = $_GET['idSyndique'];
        $syndique = $syndiqueManager->getSyndiqueById($idSyndique);
        $projet = $projetManager->getProjetById($syndique->idProjet());
        $client = $clientManager->getClientById($syndique->idClient());

ob_start();
?>
<style type="text/css">
    p, h1, h3{
        text-align: center;
        text-decoration: underline;
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
    <!--img src="../assets/img/logo_company.png" style="width: 110px" /-->
    <h3>Quittance syndique pour <?= strtoupper($client->nom()) ?> - <?= ucfirst($projet->nom()) ?></h3>
    <p>Imprimé le <?= date('d/m/Y') ?> | <?= date('h:i') ?> </p>
    <br><br>
    <table>
        <tr>
            <th style="width:50%">Date Opération</th>
            <th style="width:50%">Montant</th>
        </tr>
        <tr>
            <td style="width:50%"><?= date('d/m/Y', strtotime($syndique->date()) ) ?></td>
            <td style="width:50%"><?= number_format($syndique->montant(), 2, ',', ' ') ?></td>
        </tr>
    </table>
    <br><br><br>
    <table>
        <tr>
            <th style="width:30%">Signature</th>
        </tr>  
        <tr>
            <td style="height:70px"></td>
        </tr>
    </table>
    <br>
    <page_footer>
    <p style="text-decoration : none; text-align: center;font-size: 9pt;"></p>
    </page_footer>
</page>    
<?php
    $content = ob_get_clean();
    
    require('../lib/html2pdf/html2pdf.class.php');
    try{
        $pdf = new HTML2PDF('L', 'A5', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
        $fileName = "Quittance-Employe-".date('Ymdhi').'.pdf';
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