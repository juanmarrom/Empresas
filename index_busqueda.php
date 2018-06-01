<?php
	$conn = "";
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		/*if($_SESSION["busqueda"] == session_id()) {
			if(isset($_POST['search'])){
				$search = $_POST['search'];
				$query = "SELECT * FROM DISTRITO WHERE NOMBRE like'%".$search."%'";
				$result = mysqli_query($conn,$query);
		
				$response = array();
				while($row = mysqli_fetch_array($result) ){
					$response[] = array("value"=>$row['ID_DISTRITO'],"label"=>$row['NOMBRE']);
				}
				echo json_encode($response);
			}
		}*/
	}
?>

<!DOCTYPE html>
<html>
<head>
<!---<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>--->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
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
<script src="scripts/jquery.multiselect.filter.js"></script>

<script>

/*
var availableTags = [
"ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++",
"Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran",
"Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl",
"PHP", "Python", "Ruby", "Scala", "Scheme"
];
*/

$(document).ready(function(){
	var valor_pais = "";
	var valor_region = "";
	var valor_provincia = "";
	var valor_ciudad = "";
	var valor_distrito = "";
	var valor_barrio = "";
	var valor_calle = "";
	var valor_numero = "";

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
		alert ("Hay que seleccionar una de las opciones");
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
		alert ("Hay que seleccionar una de las opciones");
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
		alert ("Hay que seleccionar una de las opciones");
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
					response( data );
				}
			});
		},
		select: function (event, ui) {
			// Set selection
			if ($("#id_ciudad_busqueda").val() != ui.item.value && ui.item.value != -1) {            
				$("#id_ciudad_busqueda").val(ui.item.value);
				$('#id_distrito').prop("disabled", false); 
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
			alert ("Hay que seleccionar una de las opciones");
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
			alert ("Hay que seleccionar una de las opciones");
			$("#id_distrito").val("");
			deshabilitar(4);
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
			alert ("Hay que seleccionar una de las opciones");
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
			alert ("Hay que seleccionar una de las opciones");
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
			alert ("Hay que seleccionar una de las opciones");
			$("#id_numero").val("");				
		}
	});	  

	    //pais 0, region, 1, ciudad 2, distrito 3, barrio 4, calle 5, numero 6
	    function deshabilitar(a_partir_de) {
		if (a_partir_de == 0) {
			$('#id_region').prop("disabled", true);
			$('#id_region').val("");
		}    
		if (a_partir_de <= 1) {
			$('#id_provincia').prop("disabled", true);
			$('#id_provincia').val("");
		}
		if (a_partir_de <= 2) {
			$('#id_ciudad').prop("disabled", true);
			$('#id_ciudad').val("");
		}
		if (a_partir_de <= 3) {        
			$('#id_distrito').prop("disabled", true);
			$('#id_distrito').val("");
		}
		if (a_partir_de <= 4) {        
			$('#id_barrio').prop("disabled", true);
			$('#id_barrio').val("");
		}    
		if (a_partir_de <= 5) {        
			$('#id_calle').prop("disabled", true);
			$('#id_calle').val("");
		}
		if (a_partir_de <= 6) {        
			$('#id_numero').prop("disabled", true);
			$('#id_numero').val("");
		}    
	    }   

	 //http://www.erichynds.com/blog/jquery-ui-multiselect-widget 
	 //https://www.jqueryscript.net/form/jQuery-UI-Multiple-Select-Widget.html
	$("#id_grupo_actividad").multiselect({
		click: function(event, ui){
			alert(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
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
			alert(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
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
	
});


</script>

</head>
<body>
	<div id="wrap">
		<header><img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png"></header>
		<div class="container_web">
			<input type="hidden" id="id_pais_busqueda" value="-1">
			<input type="hidden" id="id_region_busqueda" value="-1">
			<input type="hidden" id="id_provincia_busqueda" value="-1">
			<input type="hidden" id="id_ciudad_busqueda" value="-1">
			<input type="hidden" id="id_distrito_busqueda" value="-1">
			<input type="hidden" id="id_barrio_busqueda" value="-1">
			<input type="hidden" id="id_calle_busqueda" value="-1">
			<input type="hidden" id="id_numero_busqueda" value="-1">
			<div id="menu" class="menu">
				<label for="">Filtros Avanzados:</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta">Pais</span>
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
				<label for="">Extras:</label>		
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta-extra">Mercado</span>
					</div>
					<input type="text" class="form-control" id="id_mercado">																	
				</div>
				<div class="input-group margen-inputs">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta-extra">C. Comercial</span>
					</div>
					<input type="text" class="form-control" id="id_ccomercial">																	
				</div>
				<div class="input-group margen-inputs">
					<div class="input-group-prepend">
						<span class="input-group-text etiqueta-extra">Galería</span>
					</div>
					<input type="text" class="form-control" id="id_galeria">																	
				</div>
				
			</div>
			
			<div id="resultado" class="panel">
				<label for="">Búsqueda de Empresas:</label>
	
				<div class="input-group margen-inputs">    					
					<nav class="navbar navbar-light bg-light ancho-total">
					  <div class="form-inline ancho-total">
						<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" >Su posición:</span>
						</div>						
						<input class="form-control mr-sm-2" type="search" placeholder="P.ej. Av. Litoral, 30 08005 Barcelona" aria-label="Posición" style="width:40%;">
						<div class="input-group-prepend">
							<span class="input-group-text" >En un radio de (en metros):</span>
						</div>						
						<input class="form-control mr-sm-2" type="search" placeholder="P.ej. 500" aria-label="Radio" style="width:10%;">
						<button class="btn btn-outline-secondary" type="submit">Caluclar</button>
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
						<!---<span>&nbsp;&nbsp;&nbsp;</span>
						<select id="id_sector" title="Basic example" multiple="multiple" name="example-basic" size="5" style="display: none;">
						<?php							
							if(isset($_SESSION["idioma"])) {								
								$query = "SELECT * FROM SECTOR WHERE ID_IDIOMA = ". $_SESSION["idioma"] . "";
								$result = mysqli_query($conn,$query);	
								$response = array();
								while($row = mysqli_fetch_array($result) ){
									echo '<option value="' . $row['ID_SECTOR'] . '">' .$row['NOMBRE'] . '</option>';
								}
								
							}
						?>
						</select>--->
						<span>&nbsp;&nbsp;&nbsp;</span>						
						<input class="form-control mr-sm-2" type="search" placeholder="Empresa" aria-label="Empresa" style="width:30%;">
						<button class="btn btn-outline-secondary" type="submit">Buscar</button>
					  </div>
					</nav>					
				</div>
				
				<div class="margen-inputs">

					<div id="id_lista_empresas" class="lista_empresas">
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>
						LISTADO <br>													
					</div>							
					<div id="id_zona_maps" class="zona_maps">
						MAPS
					</div>					
				</div>
									
			</div>	
		</div>
					
	</div>
	<footer>
	<p>©2018 LA SALLE OPEN UNIVERSITY</p>
	</footer>
	
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
