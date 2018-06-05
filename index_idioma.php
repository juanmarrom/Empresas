<?php
	$conn = "";
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			if($_SESSION['admin']) {
			}
			else {
				session_destroy();
				header("location:index.php?error=admin"); 
			}
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

<script>

$(document).ready(function(){
	$("#id_cargar").click(function() {			
		if ( $('select[id=id_app]').val() !=0 ) {
			tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
			$.ajax({
				url: "get_texto.php",
				type: 'post',
				data: { app: $('select[id=id_app]').val() },
					success: function(response){
						$("#resultado").html(response);
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
			alert ("Seleccione aplicacion");
		}		
		
	});

	$("#id_guardar").click(function() {		
		var variable = $("#id_variable").val();
		if (variable.trim() != "") {
			tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
			$.ajax({
				url: "set_variable.php",
				type: 'post',
				data: { app: $('select[id=id_app-2]').val(), variable: variable  },
					success: function(response){						
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
			alert ("Se ha de introducir una variable");
		}	
		
	});	

	tb_remove();
});

function actualizar_text(id, id_idioma) {
	if ( id !=0 ) {
		tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
		$.ajax({
			url: "set_texto.php",
			type: 'post',
			data: { id: id, texto: document.getElementById("id_text_" + id).value, id_idioma: id_idioma },
				success: function(response){
					tb_remove();				
				},				
   				error: function (request, status, error) {
        			alert("Error");
					tb_remove();
    		}			
		});
	}
	else {
		alert ("Seleccione Texto");
	}	
}

</script>
<style>
.table td {
    border: 1px solid black;
}

.table {
    border-collapse: collapse;
    width: 100%;
}

.table th {
    height: 50px;
    border: 1px solid black;
}
</style>
</head>
<body>
	<div id="wrap">
		<header>
			<div class="div-header-1">
				<img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png">
			</div>	
			<div class="div-header-2">
				<span>
					<button id="id_cerrar" class="btn btn-outline-secondary btn-header" onclick="window.close();">Cerrar</button>
				</span>						
			</div>
		</header>
		<div class="container_web">
			<div id="menu" class="menu">
				<label for="" class='text-bold'>Seleccione la aplicacion:</label>
				<select id="id_app">
					<option value="0">Ninguna</option>
					<?php
					  $result = $conn -> query ("SELECT * FROM APLICACION_WEB ");
					  while ($valores = mysqli_fetch_array($result)) {
					    echo '<option value="'.$valores[ID].'">'.$valores[NOMBRE].'</option>';
					  }
					?>
				</select>
				<br><br>
				 <button id="id_cargar" class="btn btn-outline-secondary">Cargar</button>
				<br>
				<br>

				<label class='text-bold' for="">Nueva variable:</label>
				<br><span>Aplicacion:</span><select id="id_app-2">
					<?php
					  $result = $conn -> query ("SELECT * FROM APLICACION_WEB ");
					  while ($valores = mysqli_fetch_array($result)) {
					    echo '<option value="'.$valores[ID].'">'.$valores[NOMBRE].'</option>';
					  }
					?>
				</select>
			    <br><br>
			    <span>Nombre:</span><input type="text" class="form-control" id="id_variable">
			    <br>
			    <button id="id_guardar" class="btn btn-outline-secondary">Guardar</button>
			     		
			</div>
			<div id="resultado" class="panel">

			</div>
			
		</div>			
					
	</div>
	<footer>
	<p>©2018 LA SALLE OPEN UNIVERSITY</p>
	</footer>
</body>
</html>
