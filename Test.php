<?php
  
	//echo "Tu dirección IP es: {$_SERVER['REMOTE_ADDR']}";
	$remote_addr = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$username = get_current_user();
	//
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
	$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
	$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
	$ip = $_SERVER['REMOTE_ADDR'];
	else
	$ip = "IP desconocida";
	//
	echo "</br> Nombre del Dispositivo: " .$username;
	echo "</br> Nombre completo del dispositivo: " .$remote_addr;
	
	echo "</br>IP dispositivo: " .$ip;

?>
<html>
	<head>
	
			
	<!-- jQuery 3 -->
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
	<script src='https://maps.googleapis.com/maps/api/js?sensor=false'></script>
	
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	</head>
	<body>
	
	<div id="EspacioTabla">
	
	</div>
	<script>
		/*
		$(document).ready(function(){				
			navigator.geolocation.getCurrentPosition( function(position){
				var geolocate = new google.masLatLmg(position.coords.latitude, position.coords.longitude);
				var  Latitud = position.coords.latitude;
				var  Longitud = position.coords.longitude;
				
				$("#txtLat").val(Latitud);
				$("#txtLong").val(Longitud);
			}); 
		});
		*/
	 </script>
	</body>
	
</html>