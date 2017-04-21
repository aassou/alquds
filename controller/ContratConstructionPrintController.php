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


ob_start();
?>
<style type="text/css">
    table{
        width:100%;
        border: solid 1px black;
    }
    .article{
        font-size:11pt;
        text-align: justify;
    }
    .article-title{
        text-decoration: underline;
    }
    .specification{
        font-size:10.48pt;
    }
     .specification-title{
         text-decoration: underline;
     }
</style>
<page backtop="15mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <!--img src="../assets/img/logo_company.png" style="width: 110px" /-->
    <h1 style="font-size:28px; text-align: center;">Royaume du Maroc</h1>
    <h2 style="font-size:22px; text-align: center;">Province de Nador</h2>
    <h3 style="font-size:18px; text-align: center;">Municipalité de Nador</h3>
    <br>
    <table style="margin-top:300px">
        <tr>
            <td style="font-size:30px; text-align: center; width:100%">
                CONTRAT<br>
                Objet: Contrat de Construction d'un Immeuble
            </td>
        </tr>    
    </table>
    <p style="text-decoration: underline; margin-top:400px; font-size: 18px;">Entre les sousignés:</p>
    <page_footer>
    </page_footer>
</page>    
<?php
    $content = ob_get_clean();
    
    require('../lib/html2pdf/html2pdf.class.php');
    try{
        $pdf = new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
        $fileName = "contrat-".date('Y-m-d-h-i').'.pdf';
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