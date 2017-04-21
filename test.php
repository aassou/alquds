<?php
require ('model/Caisse.php');
require ('model/CaisseManager.php');
$pdo = new PDO('mysql:host=localhost;dbname=alquds', 'root', '');
//$names = array();
$destinations = array("Bureau et Société", "Projet 1", "Plusieurs Projets 1,2,6", "Bureau", "Projet7", 
            "Bureau", "Projet 10", "Projet 4", "Société", "Plusieurs Projets 12, 11",
            "Projet 3", "Projet 12", "Projet 8", "Projets 9, 7", "Bureau");
            
$designations = array("Payer les frais de Hassan", "Société Naimi", "MP 100", "Frais Sup", "MP 150", 
            "TP 250", "Frais Transportations", "Solar En", "Neuralink", "Groupe G8",
            "Copies Croquis", "Salim Bendadah", "Frais Sup", "Assais", "Mng");

$manager = new CaisseManager($pdo);  
$elemets = $manager->getCaisses();
foreach( $elemets as $e ) {
    $manager->updateDD($destinations[array_rand($destinations)], $destinations[array_rand($destinations)],$e->id());
}
?>