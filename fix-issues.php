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
    $contratManager = new ContratManager($pdo);
    $appatementManager = new AppartementManager($pdo);        
    //objs and vars
    $contratsIdBien = $contratManager->getContratActifIdBien();
    $appartements = $appatementManager->getAppartementsNoStatus();
    $index = 0;
    print_r($contratsIdBien);
    echo "<br>";
    foreach ( $appartements as $appartement ) {
        if ( in_array($appartement->id(), $contratsIdBien) ) {
            echo "Yes<br>";
            $appatementManager->changeStatus($appartement->id(), "Vendu");
            $index++;
        }
        else {
            echo "No<br>";
        }
    }
    echo $index."<br>";
}
    