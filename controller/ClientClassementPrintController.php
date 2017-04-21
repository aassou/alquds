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
        $clientsClassementManager = new ClientClassementManager($pdo);
        $clients = $clientsClassementManager->getClientClassements();
       
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
    <h4>Classement Clients</h4>
    <table>
        <tr>
            <th style="width: 40%">Client</th>
            <th style="width: 40%">Remarque</th>
            <th style="width: 20%">Classement</th>
        </tr>
        <?php
        foreach($clients as $client){
            $classement = "";
            if ( $client->classement() == 1 ) {
                $classement = "Serieux";    
            }
            else if ( $client->classement() == 0 ) {
                $classement = "Normal";    
            }
            else if ( $client->classement() == -1 ) {
                $classement = "Litigieux";    
            }
        ?>      
        <tr>
            <td><?= $client->nom() ?></td>
            <td><?= $client->remarque() ?></td>
            <td><?= $classement ?></td>
        </tr>   
        <?php
        }//end of loop
        ?>
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
        $pdf = new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
        $fileName = "ClassementClient-".date('Ymdhi').'.pdf';
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