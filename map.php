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
    //classes loading end
    session_start();
    if ( isset($_SESSION['userMerlaTrav']) ) {
?>
<!DOCTYPE html>
<html>
  <head>
    <style>
       #map {
        height: 800px;
        width: 100%;
       }
    </style>
  </head>
  <body>
    <!--h3>My Google Maps Demo</h3-->
    <div id="map"></div>
    <script>
      function initMap() {
        var onda = {lat: 35.154559, lng: -2.919575};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: onda
        });
        //Annahda14 Begin
        var annahda14 = {lat: 35.152129, lng: -2.914972};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 14</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda14 = new google.maps.Marker({
          position: annahda14,
          map: map,
          title: 'Annahda 14'
        });
        markerAnnahda14.addListener('click', function() {
          infowindow.open(map, markerAnnahda14);
        });
        infowindow.open(map, markerAnnahda14);
        //Annahda14 End
        //Annahda13 Begin
        var annahda13 = {lat: 35.150927, lng: -2.915648};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 13</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda13 = new google.maps.Marker({
          position: annahda13,
          map: map,
          title: 'Annahda 13'
        });
        markerAnnahda13.addListener('click', function() {
          infowindow.open(map, markerAnnahda13);
        });
        infowindow.open(map, markerAnnahda13);
        //Annahda13 End
        //Annahda12 Begin
        var annahda12 = {lat: 35.154559, lng: -2.919575};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 12</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda12 = new google.maps.Marker({
          position: annahda12,
          map: map,
          title: 'Annahda 12'
        });
        markerAnnahda12.addListener('click', function() {
          infowindow.open(map, markerAnnahda12);
        });
        infowindow.open(map, markerAnnahda12);
        //Annahda12 End
        //Annahda11 Begin
        var annahda11 = {lat: 35.154682, lng: -2.923877};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 11</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda11 = new google.maps.Marker({
          position: annahda11,
          map: map,
          title: 'Annahda 11'
        });
        markerAnnahda11.addListener('click', function() {
          infowindow.open(map, markerAnnahda11);
        });
        infowindow.open(map, markerAnnahda11);
        //Annahda11 End
        //Annahda10 Begin
        var annahda10 = {lat: 35.151752, lng: -2.917375};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 10</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda10 = new google.maps.Marker({
          position: annahda10,
          map: map,
          title: 'Annahda 10'
        });
        markerAnnahda10.addListener('click', function() {
        infowindow.open(map, markerAnnahda10);
        });
        infowindow.open(map, markerAnnahda10);
        //Annahda10 End
        //Annahda9 Begin
        var annahda9 = {lat: 35.150585, lng: -2.915712};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 9</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda9 = new google.maps.Marker({
          position: annahda9,
          map: map,
          title: 'Annahda 9'
        });
        markerAnnahda9.addListener('click', function() {
        infowindow.open(map, markerAnnahda9);
        });
        infowindow.open(map, markerAnnahda9);
        //Annahda9 End
        //Annahda8G Begin
        var annahda8G = {lat: 35.147585, lng: -2.917440};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 8/G</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda8G = new google.maps.Marker({
          position: annahda8G,
          map: map,
          title: 'Annahda 8/G'
        });
        markerAnnahda8G.addListener('click', function() {
          infowindow.open(map, markerAnnahda8G);
        });
        infowindow.open(map, markerAnnahda8G);
        //Annahda8G End
        //Annahda8S Begin
        var annahda8S = {lat: 35.147383, lng: -2.917419};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 8/S</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda8S = new google.maps.Marker({
          position: annahda8S,
          map: map,
          title: 'Annahda 8/S'
        });
        markerAnnahda8S.addListener('click', function() {
          infowindow.open(map, markerAnnahda8S);
        });
        infowindow.open(map, markerAnnahda8S);
        //Annahda8S End
        //Annahda7 Begin
        var annahda7 = {lat: 35.150997, lng: -2.916925};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 7</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda7 = new google.maps.Marker({
          position: annahda7,
          map: map,
          title: 'Annahda 7'
        });
        markerAnnahda7.addListener('click', function() {
          infowindow.open(map, markerAnnahda7);
        });
        infowindow.open(map, markerAnnahda7);
        //Annahda7 End
        //Annahda6 Begin
        var annahda6 = {lat: 35.150997, lng: -2.916925};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 6</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda6 = new google.maps.Marker({
          position: annahda6,
          map: map,
          title: 'Annahda 6'
        });
        markerAnnahda6.addListener('click', function() {
          infowindow.open(map, markerAnnahda6);
        });
        infowindow.open(map, markerAnnahda6);
        //Annahda6 End
        //Annahda5 Begin
        var annahda5 = {lat: 35.147356, lng: -2.916904};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 5</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda5 = new google.maps.Marker({
          position: annahda5,
          map: map,
          title: 'Annahda 5'
        });
        markerAnnahda5.addListener('click', function() {
          infowindow.open(map, markerAnnahda5);
        });
        infowindow.open(map, markerAnnahda5);
        //Annahda5 End
        //Annahda4 Begin
        var annahda4 = {lat: 35.147932, lng: -2.910765};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 4</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda4 = new google.maps.Marker({
          position: annahda4,
          map: map,
          title: 'Annahda 4'
        });
        markerAnnahda4.addListener('click', function() {
          infowindow.open(map, markerAnnahda4);
        });
        infowindow.open(map, markerAnnahda4);
        //Annahda4 End
        //Annahda2 Begin
        var annahda2 = {lat: 35.145818, lng: -2.916655};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 2</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda2 = new google.maps.Marker({
          position: annahda2,
          map: map,
          title: 'Annahda 2'
        });
        markerAnnahda2.addListener('click', function() {
          infowindow.open(map, markerAnnahda2);
        });
        infowindow.open(map, markerAnnahda2);
        //Annahda2 End
        //Annahda1 Begin
        var annahda1 = {lat: 35.146827, lng: -2.912439};
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<p id="firstHeading" class="firstHeading"><strong>Annahda 1</strong></p>'+
            '<div id="bodyContent">'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        var markerAnnahda1 = new google.maps.Marker({
          position: annahda1,
          map: map,
          title: 'Annahda 1'
        });
        markerAnnahda1.addListener('click', function() {
          infowindow.open(map, markerAnnahda1);
        });
        infowindow.open(map, markerAnnahda1);
        //Annahda1 End
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPiRX_IPjs69eKkCxnkaSmqjEp_obZDRc&callback=initMap">
    </script>
  </body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>