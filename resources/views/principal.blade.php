<?php 


use App\Http\Controllers\EventsController;
use Illuminate\Http\Request;


$response=EventsController::validateCode($code,$ubicacion_actual,$address_destiny);
$info= json_decode($response,true);
//list of variables for view
$message = $info[0]['message'];

if(isset($info[1]['code'])){
    $codigo = $info[1]['code'];
    
}
if (isset($info[1]['quantity_travel'])) {
    $discount = $info[1]['quantity_travel'];
}
if (isset($info[1]['starts_on'])) {
    $inicio_codigo = $info[1]['starts_on'];
}
if (isset($info[1]['ends_on'])) {
    $finalizacion_codigo =$info[1]['ends_on'];
}
if (isset($info[1]['address'])) {
    $lugar =$info[1]['address'];
}
if (isset($info[1]['name'])) {
    $nombre_evento =$info[1]['name'];
}
if (isset($info[1]['ends_on'])) {
    $finalizacion_codigo =$info[1]['ends_on'];
}
if (isset($info[2]['ubicacion_actual'])) {
    $origen = $info[2]['ubicacion_actual'];
}
if (isset($info[2]['lat'])) {
    $latitud_origen = $info[2]['lat'];
}
else {
    $latitud_origen = "4.6703549";
}
if (isset($info[2]['lng'])) {
    $longitud_origen = $info[2]['lng'];
}
else{
    $longitud_origen="-74.1479869";
}
if (isset($info[3]['address_destiny'])) {
    $destiny = $info[3]['address_destiny'];
}
if (isset($info[3]['lat'])) {
    $latitud_destino =$info[3]['lat'];
}
else{
    $latitud_destino ="4.6703549";
}
if (isset($info[3]['lng'])) {
    $longitud_destino =$info[3]['lng'];
}
else {
    $longitud_destino ="-74.1479869";
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Codigos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
    <meta charset="utf-8">
    <link rel="Stylesheet" href="css/common.css" />
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    
</head>
<body>
    <main>
        <h1>Validacion completa</h1>
        <p>{{$message}}</p>
        @if($message == "Direccion origen o destino aceptada, codigo valido para realizar el viaje")
         	<p>Codigo usuado: {{$codigo}}</p>
         	<p>Evento: {{$nombre_evento}}  Direccion: {{$lugar}}</p>
        	<p>Valor descuento: {{$discount}}</p>
        	<p>Codigo valido desde: {{$inicio_codigo}} hasta {{$finalizacion_codigo}}</p>
        @endif
    </main>
    <div id="map" align="center"></div>
    <script>
    
    function initMap() {
        var request = {
            origin: "<?php if(isset($origen)){ echo $origen; } else {echo $ubicacion_actual;} ?>",
            destination: "<?php if(isset($destiny)){ echo $destiny;} else {echo $address_destiny;} ?>",
            travelMode: google.maps.DirectionsTravelMode['DRIVING'],
            unitSystem: google.maps.DirectionsUnitSystem['METRIC'],
            provideRouteAlternatives: false
        };

        map = new google.maps.Map(document.getElementById('map'));
        directionsDisplay = new google.maps.DirectionsRenderer();
        directionsService = new google.maps.DirectionsService();

        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setMap(map);
                directionsDisplay.setDirections(response);
            } else {
                document.getElementById(s).innerHTML = "Address not found";      //delete content
            }
        });
    }
    </script>
    
    

 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOoSxvmHnyb9C5WejsST-O6my3biFuqRw&callback=initMap"></script>
</body>
</html>

<!-- 
AIzaSyAOoSxvmHnyb9C5WejsST-O6my3biFuqRw --

AIzaSyBr2Sa2YgDzRqmr1bovQr9MnW0rAn-2gjY --
AIzaSyA4U-DjLjlV0-kwc65zrqKYttdIkK_KCAk
AIzaSyAdUCvaJhFogD4n6yLY9cMbeWlJ2TBEybs --
AIzaSyBMad-dkdICGV8QEpCwBcHYB1JB3BUAvcY
AIzaSyDQB93K6gpnW4G4_p54vC9_KOji3DCOM18
AIzaSyC_KLWClGgwM_xxMnmpFChON7VbtNKTR0Y
AIzaSyBaCZUPPudoGgd0n-lJyRKoHnUg6Ew1sKk
AIzaSyAjb4jWEbuaJDysH9r5UoN8yywInbmxCs8
AIzaSyBkm3WtsQlFNrrvkqpi8q8uny6fMTQV22k
 -->

