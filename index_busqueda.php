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
		   $('#id_pais').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_region').prop("disabled", false); 
		   return false;
		}
	  });


	  /*$("#id_pais").autocomplete({
		source: availableTags,
		select: function (event, ui) {
			alert (ui.item.value);
			//Activar siguiente
			$('#id_region').prop("disabled", false); 
		}		
	  });*/
	  
	  $("#id_pais").on('input', function () {
		   var val=$('#id_pais').val();
		   if (val == "") {
			$('#id_region').prop("disabled", true); 
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
					search: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
  		},
		select: function (event, ui) {
		// Set selection
		   $('#id_region').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_provincia').prop("disabled", false); 
		   return false;
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
					search: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
  		},
		select: function (event, ui) {
		// Set selection
		   $('#id_provincia').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_ciudad').prop("disabled", false); 
		   return false;
		}
	  });

	  $("#id_ciudad").autocomplete({
		source: function( request, response ) {
			// Fetch data
			//alert(request.term);
			$.ajax({
				url: "get_ciudad.php",
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
		   $('#id_ciudad').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_distrito').prop("disabled", false); 
		   return false;
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
					search: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
  		},
		select: function (event, ui) {
		// Set selection
		   $('#id_distrito').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_barrio').prop("disabled", false); 
		   return false;
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
					search: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
  		},
		select: function (event, ui) {
		// Set selection
		   $('#id_barrio').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_calle').prop("disabled", false); 
		   return false;
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
					search: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
  		},
		select: function (event, ui) {
		// Set selection
		   $('#id_calle').val(ui.item.label); // display the selected text
		   alert(ui.item.value); // save selected id to input
		   $('#id_numero').prop("disabled", false); 
		   return false;
		}
	  });
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
		}).multiselectfilter({label: 'Buscar:',placeholder: 'Texto'}
		);	

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

	$("#id_sector").multiselect({
		click: function(event, ui){
			alert(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
		},
		//header: "Grupo Actividad",	
		noneSelectedText: "Sector",
		selectedText:"# seleccionados",
		checkAllText: 'Seleccionar todo',
		uncheckAllText: 'Deseleccionar todo',		
		}).multiselectfilter({label: 'Buscar:',placeholder: 'Texto'}
		);	
	
});


</script>

</head>
<body>
	<div id="wrap">
		<header><img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png"></header>
		<div class="container_web">
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
						<select id="id_grupo_actividad" title="Basic example" multiple="multiple" name="example-basic" size="5" style="display: none;">
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
						<select id="id_actividad" title="Basic example" multiple="multiple" name="example-basic" size="5" style="display: none;">
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
						</select>	
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
