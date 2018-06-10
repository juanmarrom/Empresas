<?php
	$conn = "";
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
		}
		else {
			session_destroy();
			header("location:index.php?error=session"); 
		}
	}
	else {
		session_destroy();
		header("location:index.php?error=session"); 
	}
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="jquery-ui-1.12.1.custom/jquery-ui.structure.css">
<link rel="stylesheet" type="text/css" href="jquery-ui-1.12.1.custom/jquery-ui.theme.css">
<link rel="stylesheet" type="text/css" href="css/main_busqueda.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="css/jquery.multiselect.css">
<script src="scripts/jquery.multiselect.js"></script>
<link rel="stylesheet" href="css/jquery.multiselect.filter.css">
<link rel="stylesheet" href="css/Thickbox.css">		
<script src="scripts/jquery.multiselect.filter.js"></script>
<script src="scripts/thickbox_neu.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyCEUxuelm-ruuX7STQP7iDdk-KpoRedKCY"></script>
<link rel="shortcut icon" href="http://opencampus.uols.org/theme/image.php/lasalle1314/theme/1464558442/favicon">
<script>

var map;
var marcadores_empresas = [];
var latitud_bcn = "41.3879";
var longitud_bcn = "2.16992";


var directionsService = "";
var directionsDisplay = "";


function init_map() {
	var centro_bcn = false;
	var zoom = 15;
	var marker = "";
	if (document.getElementById("latitud").value == 0) {
		document.getElementById("latitud").value = latitud_bcn;
		document.getElementById("longitud").value = longitud_bcn;
		centro_bcn = true;
		zoom = 10;
	}

	directionsService = new google.maps.DirectionsService;
	directionsDisplay = new google.maps.DirectionsRenderer;

    var myOptions = {
        zoom: zoom,
        center: new google.maps.LatLng(document.getElementById("latitud").value,document.getElementById("longitud").value),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
    infowindow = new google.maps.InfoWindow({
        content: document.getElementById("direccion").value
    });    
	if (!centro_bcn) {
	    marker = new google.maps.Marker({
	        map: map,
	        position: new google.maps.LatLng(document.getElementById("latitud").value, document.getElementById("longitud").value)
	    });
	    google.maps.event.addListener(marker, "click", function () {
	        infowindow.open(map, marker);
	    });
	}
	if (document.getElementById("latitud").value != 0) {
		directionsDisplay.setMap(map);
	}
    infowindow.open(map, marker);
	if (centro_bcn) {
		document.getElementById("latitud").value = 0;
		document.getElementById("longitud").value = 0;
	} 
}	

var beaches = [
  ['Bondi Beach', -33.890542, 151.274856, 4],
  ['Coogee Beach', -33.923036, 151.259052, 5],
  ['Cronulla Beach', -34.028249, 151.157507, 3],
  ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
  ['Maroubra Beach', -33.950198, 151.259302, 1]
];

function setMarkers(map) {
  // Adds markers to the map.

  // Marker sizes are expressed as a Size of X,Y where the origin of the image
  // (0,0) is located in the top left of the image.

  // Origins, anchor positions and coordinates of the marker increase in the X
  // direction to the right and in the Y direction down.
 
  // Shapes define the clickable region of the icon. The type defines an HTML
  // <area> element 'poly' which traces out a polygon as a series of X,Y points.
  // The final coordinate closes the poly by connecting to the first coordinate.
  var shape = {
    coords: [1, 1, 1, 20, 18, 20, 18, 1],
    type: 'poly'
  };

  for (var i = 0; i < marcadores_empresas.length; i++) {
	 var image = {
	    url: 'images/marker' + (i + 1) + '.png',
	    // This marker is 20 pixels wide by 32 pixels high.
	    size: new google.maps.Size(32, 32),
	    // The origin for this image is (0, 0).
	    origin: new google.maps.Point(0, 0),
	    // The anchor for this image is the base of the flagpole at (0, 32).
	    anchor: new google.maps.Point(0, 32)
	  };

    var beach = marcadores_empresas[i];

    var marker = new google.maps.Marker({
      position: {lat: beach[1], lng: beach[2]},
      map: map,
      icon: image,
      shape: shape,
      title: beach[0],
      zIndex: beach[3]
    });
    var id_distancia = "id_distancia_result_" + (i + 1);
    var distancia = 0;
    if (document.getElementById(id_distancia)) {
    	distancia = document.getElementById(id_distancia).value;
    }

    var nombre = beach[0];
    var lati = beach[1];
    var longi = beach[2];
	google.maps.event.addListener(marker,'click', (function(marker, nombre, lati, longi, distancia){ 
	    return function() {
	        //alert(nombre);
	        
	        calculateAndDisplayRoute(directionsService, directionsDisplay, lati, longi, distancia); 
	        

 			var contentString = "<b>" + nombre + "</b><br/>";

        	var infowindow = new google.maps.InfoWindow({
          		content: contentString
        	});	        
	        infowindow.open(map, marker);
	    };
	})(marker,nombre, lati, longi, distancia));      

	  }
}	

function calculateAndDisplayRoute(directionsService, directionsDisplay, lat, long, distancia) {
	var origin_lat = parseFloat(document.getElementById("latitud").value);
	var origin_long = parseFloat(document.getElementById("longitud").value);
	var travelMode = 'DRIVING';
	if (distancia < 300) {
		travelMode = 'WALKING';
	}
		if (origin_lat != 0) {
		directionsService.route({
		origin: {lat: origin_lat, lng: origin_long},  // Haight.
		destination: {lat: lat, lng: long},  // Ocean Beach.
		// Note that Javascript allows us to access the constant
		// using square brackets and a string value as its
		// "property."
		travelMode: travelMode
		}, function(response, status) {
			if (status == 'OK') {
				directionsDisplay.setDirections(response);
			} 
			else {
				alert('Error ' + status);
			}
		});
	}
}	

function pasar_pagina(pagina) {
	tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
	$.ajax({
		url: "pagina.php",
		type: 'post',
		dataType: "html",
		data: {
			pagina: pagina,
		},
		success: function( data ) {
			if (data.indexOf("Session") >= 0) {
				location.href="index.php?error=session";
			}
			$("#id_lista_empresas").html(data);
			var j = 0;
			marcadores_empresas = [];
			for (var i=10; i > 0; i--) {
				if ( $("#id_empresa_result_" + i) ) {
						var marca = new Array($("#id_empresa_result_" + i).val(), parseFloat($("#id_latitud_result_" + i).val()), parseFloat($("#id_longitud_result_" + i).val()), j);
						marcadores_empresas.push(marca);
						j++;
				}
			}
			init_map();
			setMarkers(map);
			tb_remove();
		},
		error: function (request, status, error) {
		//alert(request.responseText);	
		tb_remove();
		}			
	});
}

$(document).ready(function(){
	tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
	
	

	var valor_pais = "";
	var valor_region = "";
	var valor_provincia = "";
	var valor_ciudad = "";
	var valor_distrito = "";
	var valor_barrio = "";
	var valor_calle = "";
	var valor_numero = "";

	var valor_mercado = "";
	var valor_ccomercial = "";
	var valor_galeria = "";




	$("#id_pais").click(function() {
		valor_pais = $("#id_pais").val();
	});		
	$("#id_region").click(function() {
		valor_region = $("#id_region").val();
	});		
	$("#id_provincia").click(function() {
		valor_provincia = $("#id_provincia").val();
	});		
	$("#id_ciudad").click(function() {
		valor_ciudad = $("#id_ciudad").val();
	});		
	$("#id_distrito").click(function() {
		valor_distrito = $("#id_distrito").val();
	});		
	$("#id_barrio").click(function() {
		valor_barrio = $("#id_barrio").val();
	});		
	$("#id_calle").click(function() {
		valor_calle = $("#id_calle").val();
	});		
	$("#id_numero").click(function() {
		valor_numero = $("#id_numero").val();
	});	

	$("#id_mercado").click(function() {
		valor_mercado = $("#id_mercado").val();
	});
	$("#id_ccomercial").click(function() {
		valor_ccomercial = $("#id_ccomercial").val();
	});
	$("#id_galeria").click(function() {
		valor_galeria = $("#id_galeria").val();
	});				
	

				//id_actividad: $("#id_actividad_busqueda").val(),
				//id_mercado: $("#id_mercado_busqueda").val(),
				//id_ccomercial: $("#id_ccomercial_busqueda").val(),
	$("#id_galeria").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_galeria.php",
				type: 'post',
				dataType: "json",
				data: {
					id_ciudad: $("#id_ciudad_busqueda").val(),
					search: request.term
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
		    if ($("#id_galeria_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_galeria_busqueda").val(ui.item.value); 
				$('#id_mercado').prop("disabled", true); 
				$('#id_ccomercial').prop("disabled", true); 
				$("#id_id_mercado_busqueda").val(-1); 
				$("#id_ccomercial_busqueda").val(-1); 
		    }
		    valor_mercado = ui.item.label;
		    $('#id_galeria').val(ui.item.label);
		    return false;
		},
		open: function(){
		    //console.log("open");
		    $("#id_galeria_busqueda").val(-1);
		}
	});
	$("#id_galeria").blur(function() {
	    //console.log("blur");
	    if ($("#id_galeria_busqueda").val() == -1 || valor_mercado != $("#id_galeria").val()) {
			
			$("#id_galeria").val("");
			$("#id_galeria_busqueda").val(-1);
			$('#id_mercado').prop("disabled", false); 
			$('#id_ccomercial').prop("disabled", false); 					
	    }
	});





	$("#id_ccomercial").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_ccomercial.php",
				type: 'post',
				dataType: "json",
				data: {
					id_ciudad: $("#id_ciudad_busqueda").val(),
					search: request.term
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
		    if ($("#id_ccomercial_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_ccomercial_busqueda").val(ui.item.value); 
				$('#id_mercado').prop("disabled", true); 
				$('#id_galeria').prop("disabled", true); 
				$("#id_id_mercado_busqueda").val(-1); 
				$("#id_galeria_busqueda").val(-1); 
		    }
		    valor_mercado = ui.item.label;
		    $('#id_ccomercial').val(ui.item.label);
		    return false;
		},
		open: function(){
		    //console.log("open");
		    $("#id_ccomercial_busqueda").val(-1);
		}
	});
	$("#id_ccomercial").blur(function() {
	    //console.log("blur");
	    if ($("#id_ccomercial_busqueda").val() == -1 || valor_mercado != $("#id_ccomercial").val()) {
			
			$("#id_ccomercial").val("");
			$("#id_ccomercial_busqueda").val(-1);
			$('#id_mercado').prop("disabled", false); 
			$('#id_galeria').prop("disabled", false); 					
	    }
	});


	$("#id_mercado").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_mercado.php",
				type: 'post',
				dataType: "json",
				data: {
					id_ciudad: $("#id_ciudad_busqueda").val(),
					search: request.term
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
		    if ($("#id_mercado_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_mercado_busqueda").val(ui.item.value); 
				$('#id_ccomercial').prop("disabled", true); 
				$('#id_galeria').prop("disabled", true); 
				$("#id_ccomercial_busqueda").val(-1); 
				$("#id_galeria_busqueda").val(-1); 
		    }
		    valor_mercado = ui.item.label;
		    $('#id_mercado').val(ui.item.label);
		    return false;
		},
		open: function(){
		    //console.log("open");
		    $("#id_mercado_busqueda").val(-1);
		}
	});
	$("#id_mercado").blur(function() {
	    //console.log("blur");
	    if ($("#id_mercado_busqueda").val() == -1 || valor_mercado != $("#id_mercado").val()) {
			
			$("#id_mercado").val("");
			$("#id_mercado_busqueda").val(-1);
			$('#id_ccomercial').prop("disabled", false); 
			$('#id_galeria').prop("disabled", false); 				
	    }
	});

	
	$("#id_pais").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_pais.php",
				type: 'post',
				dataType: "json",
				data: {
					search: request.term
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
		    // Set selection		    
		   // display the selected text
		    //console.log ($("#id_pais_busqueda").val() + " / " + ui.item.value + " / " + ui.item.label);
		    if ($("#id_pais_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_pais_busqueda").val(ui.item.value);
				$('#id_region').prop("disabled", false); 
		    }
		    valor_pais = ui.item.label;
		    $('#id_pais').val(ui.item.label);
		    return false;
		},
		open: function(){
		    //console.log("open");
		    $("#id_pais_busqueda").val(-1);
		}
	});
	$("#id_pais").blur(function() {
	    //console.log("blur");
	    if ($("#id_pais_busqueda").val() == -1 || valor_pais != $("#id_pais").val()) {
			$("#id_pais_busqueda").val(-1);
			$("#id_pais").val("");
			deshabilitar(0);
	    }
	});


	$("#id_region").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_region.php",
				type: 'post',
				dataType: "json",
				data: {
					search: request.term,
					id_pais: $("#id_pais_busqueda").val()
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
			// Set selection		 
			if ($("#id_region_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_region_busqueda").val(ui.item.value);
				$('#id_provincia').prop("disabled", false); 
			}
			valor_region = ui.item.label;
			$('#id_region').val(ui.item.label);
			return false;
		},
		open: function(){
		    $("#id_region_busqueda").val(-1);
		}
	});

	$("#id_region").blur(function() {
	    if ($("#id_region_busqueda").val() == -1 || valor_region != $("#id_region").val()) {
		$("#id_region_busqueda").val(-1);
		$("#id_region").val("");
		deshabilitar(1);
	    }
	});

	$("#id_provincia").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_provincia.php",
				type: 'post',
				dataType: "json",
				data: {
					search: request.term,
					id_region: $("#id_region_busqueda").val()
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
			// Set selection		 
			if ($("#id_provincia_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_provincia_busqueda").val(ui.item.value);
				$('#id_ciudad').prop("disabled", false); 
			}
			valor_provincia = ui.item.label;
			$('#id_provincia').val(ui.item.label);	
			return false;	
		},
		open: function(){
		    $("#id_provincia_busqueda").val(-1);
		}
	});

	$("#id_provincia").blur(function() {
	    if ($("#id_provincia_busqueda").val() == -1 || valor_provincia != $("#id_provincia").val()) {
		$("#id_provincia_busqueda").val(-1);
		$("#id_provincia").val("");
		deshabilitar(2);
	    }
	});


	$("#id_ciudad").autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "get_ciudad.php",
				type: 'post',
				dataType: "json",
				data: {	
					search: request.term,
					id_provincia: $("#id_provincia_busqueda").val()
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
			// Set selection
			if ($("#id_ciudad_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_ciudad_busqueda").val(ui.item.value);
				$('#id_distrito').prop("disabled", false); 
				$('#id_mercado').prop("disabled", false); 
				$('#id_galeria').prop("disabled", false); 
				$('#id_ccomercial').prop("disabled", false); 
			}
			valor_ciudad = ui.item.label;
			$('#id_ciudad').val(ui.item.label);
		  	return false;
		},        
		open: function(){
			$("#id_ciudad_busqueda").val(-1);
		},
	});
	$("#id_ciudad").blur(function() {
		if ($("#id_ciudad_busqueda").val() == -1 || valor_ciudad != $("#id_ciudad").val()) {
			$("#id_ciudad_busqueda").val(-1);
			$("#id_ciudad").val("");
			deshabilitar(3);
		}
	});


	$("#id_distrito").autocomplete({
		source: function( request, response ) {
		// Fetch data
		//alert(request.term);
		$.ajax({
			url: "get_distrito.php",
			type: 'post',
			dataType: "json",
			data: {
				search: request.term,
				id_ciudad: $("#id_ciudad_busqueda").val()
			},
			success: function( data ) {
				if (data.indexOf("Session") >= 0) {
					location.href="index.php?error=session";
				}				
				response( data );
			}
		});

		},
		select: function (event, ui) {
			if ($("#id_distrito_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_distrito_busqueda").val(ui.item.value);
				$('#id_barrio').prop("disabled", false); 
			}
			valor_distrito = ui.item.label;
			$('#id_distrito').val(ui.item.label);
			return false;			
		},        
		open: function(){
			$("#id_distrito_busqueda").val(-1);
		},
	});
	$("#id_distrito").blur(function() {
		if ($("#id_distrito_busqueda").val() == -1 || valor_distrito != $("#id_distrito").val()) {
			$("#id_distrito_busqueda").val(-1);
			$("#id_distrito").val("");
			deshabilitar(4);
			habilitar_extras();
		}
		else {
			limpiar_extras();
		}
	});

		  
	$("#id_barrio").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_barrio.php",
				type: 'post',
				dataType: "json",
				data: {
					search: request.term,
					id_distrito: $("#id_distrito_busqueda").val()
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
			if ($("#id_barrio_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_barrio_busqueda").val(ui.item.value);
				$('#id_calle').prop("disabled", false); 
			}
			valor_barrio = ui.item.label;
			$('#id_barrio').val(ui.item.label);
			return false;			
		},
		open: function(){
			$("#id_barrio_busqueda").val(-1);
		},
	});
	$("#id_barrio").blur(function() {
		if ($("#id_barrio_busqueda").val() == -1 || valor_barrio != $("#id_barrio").val()) {
			$("#id_barrio_busqueda").val(-1);
			$("#id_barrio").val("");
			deshabilitar(5);
		}
	});		

	$("#id_calle").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_calle.php",
				type: 'post',
				dataType: "json",
				data: {
					search: request.term,
					id_barrio: $("#id_barrio_busqueda").val()
				},
				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
			if ($("#id_calle_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_calle_busqueda").val(ui.item.value);
				$('#id_numero').prop("disabled", false); 
			}
			valor_calle = ui.item.label;
			$('#id_calle').val(ui.item.label);
			return false;		
		
		
		// Set selection
		   $('#id_calle').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_numero').prop("disabled", false); 
		   return false;
		},
		open: function(){
			$("#id_calle_busqueda").val(-1);
		},

	});
	$("#id_calle").blur(function() {
		if ($("#id_calle_busqueda").val() == -1 || valor_calle != $("#id_calle").val()) {
			$("#id_calle_busqueda").val(-1);
			$("#id_calle").val("");
			deshabilitar(6);
		}
	});	  

	$("#id_numero").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_numero_calle.php",
				type: 'post',
				dataType: "json",
				data: {
					search: request.term,
					id_calle: $("#id_calle_busqueda").val()
				},

				success: function( data ) {
					if (data.indexOf("Session") >= 0) {
						location.href="index.php?error=session";
					}					
					response( data );
				}
			});
		},
		select: function (event, ui) {
			if ($("#id_numero_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_numero_busqueda").val(ui.item.value);					
			}
			valor_numero = ui.item.label;
			$('#id_numero').val(ui.item.label);
			return false;			
		},
		open: function(){
			$("#id_numero_busqueda").val(-1);
		},			
	});
	$("#id_numero").blur(function() {
		if ($("#id_numero_busqueda").val() == -1 || valor_numero != $("#id_numero").val()) {
			$("#id_numero_busqueda").val(-1);
			$("#id_numero").val("");				
		}
	});	  


	function limpiar_extras() {
		$('#id_mercado').prop("disabled", true);
		$('#id_ccomercial').prop("disabled", true);
		$('#id_galeria').prop("disabled", true);
		
		$('#id_mercado').val("");
		$('#id_ccomercial').val("");
		$('#id_galeria').val("");

		$("#iid_mercado_busqueda").val(-1);
		$("#id_ccomercial_busqueda").val(-1);
		$("#id_galeria_busqueda").val(-1);		
	}

	function habilitar_extras() {
		$('#id_mercado').prop("disabled", false);
		$('#id_ccomercial').prop("disabled", false);
		$('#id_galeria').prop("disabled", false);
		
		$('#id_mercado').val("");
		$('#id_ccomercial').val("");
		$('#id_galeria').val("");

		$("#iid_mercado_busqueda").val(-1);
		$("#id_ccomercial_busqueda").val(-1);
		$("#id_galeria_busqueda").val(-1);		
	}

    //pais 0, region, 1, ciudad 2, distrito 3, barrio 4, calle 5, numero 6
    function deshabilitar(a_partir_de) {
		if (a_partir_de == 0) {
			$('#id_region').prop("disabled", true);
			$('#id_region').val("");
			$("#id_region_busqueda").val(-1);
		}    
		if (a_partir_de <= 1) {
			$('#id_provincia').prop("disabled", true);
			$('#id_provincia').val("");
			$("#id_provincia_busqueda").val(-1);
		}
		if (a_partir_de <= 2) {
			$('#id_ciudad').prop("disabled", true);
			$('#id_ciudad').val("");
			$("#id_ciudad_busqueda").val(-1);
		}
		if (a_partir_de <= 3) {        
			$('#id_distrito').prop("disabled", true);
			$('#id_distrito').val("");
			$("#id_distrito_busqueda").val(-1);
		}
		if (a_partir_de <= 4) {        
			$('#id_barrio').prop("disabled", true);
			$('#id_barrio').val("");
			$("#id_barrio_busqueda").val(-1);
		}    
		if (a_partir_de <= 5) {        
			$('#id_calle').prop("disabled", true);
			$('#id_calle').val("");
			$("#id_calle_busqueda").val(-1);
		}
		if (a_partir_de <= 6) {        
			$('#id_numero').prop("disabled", true);
			$('#id_numero').val("");
			$("#id_numero_busqueda").val(-1);
		}    

		if (a_partir_de != 3) { 
			limpiar_extras();
		}
    }   

	 //http://www.erichynds.com/blog/jquery-ui-multiselect-widget 
	 //https://www.jqueryscript.net/form/jQuery-UI-Multiple-Select-Widget.html
	$("#id_grupo_actividad").multiselect({
		click: function(event, ui){
			//alert(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
		},
		noneSelectedText: "Grupo Actividad",
		selectedText:"# seleccionados",
		checkAllText: 'Seleccionar todo',
		uncheckAllText: 'Deseleccionar todo',
		menuWidth:300
		}).multiselectfilter({label: 'Buscar:',placeholder: 'Texto'}
		);

	$('#id_grupo_actividad_ms').css('width', '290px');	

	$("#id_actividad").multiselect({
		click: function(event, ui){
			//alert(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );			
		},
		noneSelectedText: "Actividad",
		selectedText:"# seleccionados",
		checkAllText: 'Seleccionar todo',
		uncheckAllText: 'Deseleccionar todo',		
		}).multiselectfilter({label: 'Buscar:',placeholder: 'Texto'}
	);	

	$('#id_actividad_ms').css('width', '220px');

	/*$("#id_sector").multiselect({
		click: function(event, ui){
			alert(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
		},
		//header: "Grupo Actividad",	
		noneSelectedText: "Sector",
		selectedText:"# seleccionados",
		checkAllText: 'Seleccionar todo',
		uncheckAllText: 'Deseleccionar todo',		
		}).multiselectfilter({label: 'Buscar:',placeholder: 'Texto'}
		);	*/

	$("#id_buscar").click(function() {
		if ($("#id_ciudad_busqueda").val() == -1) {
			alert("Hay que elegir alguna ciudad");
			return;
		}

		if ($("#id_radio").val() > 10000 || $("#id_radio").val() < 0) {
			alert("El radio máximo es de 10.000 metros");
			return;
		}

		if ($("#id_nombre_empresa").val().trim() == "" && ($("#latitud").val() == 0 || $("#id_radio").val() == "" ) ) {
			alert("Sin nombre de empresa solo se puede buscar en un radio de 0 a 10.000 metros");
			return;
		}				

		tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
		
		if ($('#id_actividad').val() == null) {
			$("#id_actividad_busqueda").val(-1);
		}
		else {
			$("#id_actividad_busqueda").val($('#id_actividad').val());
		}

		if ($('#id_grupo_actividad').val() == null) {
			$("#id_grupo_actividad_busqueda").val(-1);
		}
		else {
			$("#id_grupo_actividad_busqueda").val($('#id_grupo_actividad').val());
		}		
		var no_activa = -1;

		if ($("#id_no_activas")) {
			if ($('#id_no_activas').prop('checked')) {
				no_activa = 1;
			}
		}

		$.ajax({
			url: "buscar.php",
			type: 'post',
			dataType: "html",
			data: {
				id_pais: $("#id_pais_busqueda").val(),
				id_region: $("#id_region_busqueda").val(),
				id_provincia: $("#id_provincia_busqueda").val(),
				id_ciudad: $("#id_ciudad_busqueda").val(),
				id_distrito: $("#id_distrito_busqueda").val(),
				id_barrio: $("#id_barrio_busqueda").val(),
				id_calle: $("#id_calle_busqueda").val(),
				id_numero: $("#id_numero_busqueda").val(),
				id_grupo_actividad: $("#id_grupo_actividad_busqueda").val(),
				id_actividad: $("#id_actividad_busqueda").val(),
				id_mercado: $("#id_mercado_busqueda").val(),
				id_ccomercial: $("#id_ccomercial_busqueda").val(),
				id_galeria: $("#id_galeria_busqueda").val(),
				nombre_empresa: $("#id_nombre_empresa").val().trim(),
				latitud_user: $("#latitud").val(),
				longitud_user: $("#longitud").val(),
				direccion_user: $("#direccion").val(),
				no_activa: no_activa,
				radio: $("#id_radio").val()
			},
			success: function( data ) {
				if (data.indexOf("Session") >= 0) {
					location.href="index.php?error=session";
				}
				$("#id_lista_empresas").html(data);
				//google.maps.event.addDomListener(window, 'load', init_map);
				var j = 0;
				marcadores_empresas = [];
				for (var i=10; i > 0; i--) {
					if ( $("#id_empresa_result_" + i) ) {
  						var marca = new Array($("#id_empresa_result_" + i).val(), parseFloat($("#id_latitud_result_" + i).val()), parseFloat($("#id_longitud_result_" + i).val()), j);
  						marcadores_empresas.push(marca);
  						j++;
					}
				}
				init_map();
				setMarkers(map);
				tb_remove();
			},
   			error: function (request, status, error) {
        		//alert(request.responseText);	
				tb_remove();
    		}			
		});
		
	});

	$("#id_calcular").click(function() {
		if($("#id_posicion").val().trim() != "") {
			tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
			$.ajax({
				url: "get_coordenadas.php",
				type: 'post',
				data: { address: $("#id_posicion").val()  },
					success: function(response){
						//alert (response);
						var myObj = JSON.parse(response);
						if (myObj.latitude != "" && myObj.latitude != null && myObj.latitude != "undefined") {
							$("#latitud").val(myObj.latitude);
							$("#longitud").val(myObj.longitude);
							$("#direccion").val(myObj.formatted_address);
							$("#id_enlace_posicion").attr("href", "https://www.google.com/maps?q=" + myObj.formatted_address);
							$("#id_enlace_posicion").css("visibility", "visible");
							alert("Su posición se ha podido determinar con éxito");
						}
						else {
							alert("No se ha podido determinar su posición");
						}
						tb_remove();				
					},				
	   				error: function (request, status, error) {
	        			//alert(request.responseText);
	        			alert("Error");
						tb_remove();
	    		}			
			});
		}
		else {
			$("#id_posicion").val("");
			alert ("Por favor introduzca una posición válida");
		}
		
	});

	$("#id_resetear").click(function() {
		$("#latitud").val(0);
		$("#longitud").val(0);
		$("#direccion").val("");
		$("#id_posicion").val("");		
		$("#id_radio").val("");		
		$("#id_enlace_posicion").css("visibility", "hidden");		
	});	


  	$( function() {
	    $( "#ayuda" ).dialog({
	      autoOpen: false,
	      show: {
	        effect: "blind",
	        duration: 1000
	      },
	      hide: {
	        effect: "explode",
	        duration: 1000
	      }
	    });

	    $( "#ayuda_2" ).dialog({
	      autoOpen: false,
	      show: {
	        effect: "blind",
	        duration: 1000
	      },
	      hide: {
	        effect: "explode",
	        duration: 1000
	      }
	    });	    
	 
	    $( "#opener" ).on( "click", function() {
	      $( "#ayuda" ).dialog( "open" );
	    });

	    $( "#opener_2" ).on( "click", function() {
	      $( "#ayuda" ).dialog( "open" );
	    });

	    $( "#opener_3" ).on( "click", function() {
	      $( "#ayuda_2" ).dialog( "open" );
	    });	    
  } );


	tb_remove();
});


</script>

</head>
<body>
	<div id="wrap">
		<header>
			<div class="div-header-1">
				<img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png" onclick="location.reload();">
			</div>	
			<div class="div-header-2">
			
			<?php							
				if($_SESSION["admin"]) {
					echo '<span style="padding-right: 30px;">
						<button class="btn btn-outline-secondary btn-header" onclick="window.open(\'index_usuario.php\', \'_blank\');">Usuarios</button>
					</span>			
					<span style="padding-right: 30px;">
						<button class="btn btn-outline-secondary btn-header" onclick="window.open(\'index_logs.php\', \'_blank\');"   >Logs</button>
					</span>
					<span style="padding-right: 30px;">
						<button class="btn btn-outline-secondary btn-header" onclick="window.open(\'index_idioma.php\', \'_blank\');">Textos</button>
					</span>';
				}
			?>
				<span>
					<button id="id_cambio" class="btn btn-outline-secondary btn-header" onclick="window.open('cambiar_clave.php', '_blank');">Contraseña</button>
				</span>			
				<span>
					<button id="id_salir" class="btn btn-outline-secondary btn-header" onclick="location.href ='salir.php';">Salir</button>
				</span>						
			</div>
		</header>
		<div class="container_web">
			<input type="hidden" id="id_pais_busqueda" value="-1">
			<input type="hidden" id="id_region_busqueda" value="-1">
			<input type="hidden" id="id_provincia_busqueda" value="-1">
			<input type="hidden" id="id_ciudad_busqueda" value="-1">
			<input type="hidden" id="id_distrito_busqueda" value="-1">
			<input type="hidden" id="id_barrio_busqueda" value="-1">
			<input type="hidden" id="id_calle_busqueda" value="-1">
			<input type="hidden" id="id_numero_busqueda" value="-1">		
			<input type="hidden" id="id_grupo_actividad_busqueda" value="-1">
			<input type="hidden" id="id_actividad_busqueda" value="-1">
			<input type="hidden" id="id_mercado_busqueda" value="-1">
			<input type="hidden" id="id_galeria_busqueda" value="-1">
			<input type="hidden" id="id_ccomercial_busqueda" value="-1">
			<input type="hidden" id="latitud" value="0">
			<input type="hidden" id="longitud" value="0">
			<input type="hidden" id="direccion" value="">
			<input type="hidden" id="radio" value="0">
			<div id="menu" class="menu">
				<label class="text-bold" for="">Filtros de Localización</label>&nbsp;<i id="opener" class="fas fa-question-circle"></i>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">País</span>
					</div>
					<input type="text" class="form-control" id="id_pais">
				</div>				
				<div class="input-group margen-inputs">					
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Región</span>
					</div>
					<input type="text" class="form-control" id="id_region" disabled>
				</div>				
				<div class="input-group margen-inputs">					
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Provincia</span>
					</div>
					<input type="text" class="form-control" id="id_provincia" disabled>
				</div>				
				<div class="input-group margen-inputs">					
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Ciudad</span>
					</div>
					<input type="text" class="form-control" id="id_ciudad" disabled>
				</div>				
				<div class="input-group margen-inputs">					
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Distrito</span>
					</div>
					<input type="text" class="form-control" id="id_distrito" disabled>												
				</div>				
				<div class="input-group margen-inputs">					
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Barrio</span>
					</div>
					<input type="text" class="form-control" id="id_barrio" disabled>												
				</div>				
				<div class="input-group margen-inputs">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Calle</span>
					</div>
					<input type="text" class="form-control" id="id_calle" disabled>												
				</div>				
				<div class="input-group margen-inputs">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Número</span>
					</div>
					<input type="text" class="form-control" id="id_numero" disabled>																	
				</div>
				<br>
				<label class="text-bold" for="">Recintos Comerciales</label>&nbsp;<i id="opener_2" class="fas fa-question-circle"></i>	
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta-extra">Mercado</span>
					</div>
					<input type="text" class="form-control" id="id_mercado" disabled>																	
				</div>
				<div class="input-group margen-inputs">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta-extra">C. Comercial</span>
					</div>
					<input type="text" class="form-control" id="id_ccomercial" disabled>																	
				</div>
				<div class="input-group margen-inputs">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta-extra">Galería</span>
					</div>
					<input type="text" class="form-control" id="id_galeria" disabled>																	
				</div>
				
			</div>
			
			<div id="resultado" class="panel">
				<label class="text-bold" for="">Búsqueda de Empresas</label>&nbsp;<i id="opener_3" class="fas fa-question-circle"></i>
	
				<div class="input-group margen-inputs">    					
					<nav class="navbar navbar-light bg-light ancho-total">
					  <div class="form-inline ancho-total">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Su posición:</span>
							</div>						
							<input id="id_posicion" class="form-control mr-sm-2" type="search" placeholder="P.ej. Av. Litoral, 30 08005 Barcelona" style="width:30%;">
							<button id="id_calcular" class="btn btn-outline-secondary">Calcular</button>
							&nbsp;
							<div class="input-group-prepend">
								<span class="input-group-text" >En un radio de (en metros):</span>
							</div>						
							<input id="id_radio" class="form-control mr-sm-2" type="search" placeholder="P.ej. 500" style="width:5%;">
							<button id="id_resetear" class="btn btn-outline-secondary">Resetear Posición</button>		
							<a id="id_enlace_posicion" style="visibility:hidden;" target='_blank'>
								<i class='fas fa-map-marker-alt clase_iconos'></i>									 				
							</a>
					 	</div>
					  </div>
					  
					</nav>					
				</div>

		
				<div class="input-group">    					
					<nav class="navbar navbar-light bg-light ancho-total">
					  <div class="form-inline ancho-total">
						<select id="id_grupo_actividad" title="Grupo Actividad" multiple="multiple" name="example-basic" size="5" style="display: none;">
						<?php							
							if(isset($_SESSION["idioma"])) {								
								$query = "SELECT * FROM GRUPO_ACTIVIDAD WHERE ID_IDIOMA = ". $_SESSION["idioma"] . "";
								$result = mysqli_query($conn,$query);	
								$response = array();
								while($row = mysqli_fetch_array($result) ){
									echo '<option value="' . $row['ID_GRUPO_ACTIVIDAD'] . '">' .$row['NOMBRE'] . '</option>';
								}
								
							}
						?>
						</select>
						<span>&nbsp;&nbsp;&nbsp;</span>
						<select id="id_actividad" title="Actividad" multiple="multiple" name="example-basic" size="5" style="display: none;">
						<?php							
							if(isset($_SESSION["idioma"])) {								
								$query = "SELECT * FROM ACTIVIDAD WHERE ID_IDIOMA = ". $_SESSION["idioma"] . "";
								$result = mysqli_query($conn,$query);	
								$response = array();
								while($row = mysqli_fetch_array($result) ){
									echo '<option value="' . $row['ID_ACTIVIDAD'] . '">' .$row['NOMBRE'] . '</option>';
								}
								
							}
						?>
						</select>
						<span>&nbsp;&nbsp;&nbsp;</span>						
						<input id="id_nombre_empresa" class="form-control mr-sm-2" type="search" placeholder="Empresa" aria-label="Empresa" style="width:30%;">
						<button id="id_buscar" class="btn btn-outline-secondary">Buscar</button>
						<?php
							if ($_SESSION['admin']) {
								echo "<br><br><span>&nbsp;Solo empresas no Activas&nbsp;</span><input type='checkbox' id='id_no_activas'>";	
							}
						?>							
						
					  </div>
					</nav>					
				</div>
				
				<div class="margen-inputs">

					<div id="id_lista_empresas" class="lista_empresas">
						<?php
						echo "<span class='text-estandar-empresa'>&nbsp;&nbsp;&nbsp;Bienvenido " . $_SESSION['nombre'] . " " . $_SESSION['apellido_1'] . " " . $_SESSION['apellido_2'] . "</span>";	
						?>					
					</div>							
					<div id="id_zona_maps" class="zona_maps">
						<div id="gmap_canvas"></div>
						<div id='map-label'></div>
					</div>					
				</div>
									
			</div>	
		</div>
					
	</div>
	<footer>
	<p>©2018 LA SALLE OPEN UNIVERSITY</p>
	</footer>
	<div id="ayuda" title="Ayuda">
	  <p>Tiene que seleccionar una de las opciones proporcionadas. No es posible el uso de texto libre.</p>
	</div>
	<div id="ayuda_2" title="Ayuda">
	  <p>Puede introducir la posición desde donde quiere realizar una búsqueda radial. El resultado de empresas está limitado a 5.000. Las opciones Grupo Actividad y Actividad no son excluyentes entre ellas.</p>
	</div>	
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var mySelect = $('#first-disabled2');
    $('#special').on('click', function () {
      mySelect.find('option:selected').prop('disabled', true);
      mySelect.selectpicker('refresh');
    });
    $('#special2').on('click', function () {
      mySelect.find('option:disabled').prop('disabled', false);
      mySelect.selectpicker('refresh');
    });
    $('#basic2').selectpicker({
      liveSearch: true,
      maxOptions: 1
    });
  });
</script>	
	
</body>
</html>
