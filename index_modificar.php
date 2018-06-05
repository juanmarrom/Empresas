<?php
	$conn = "";
	$id = 0;
	$idioma = "";
	$empresa;
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			if($_SESSION['admin']) {
				$id = $_GET['id'];
				if (!is_numeric($id)) {
					session_destroy();
					header("location:index.php?error=ataque"); 
				}
				else {
					$idioma = $_SESSION['idioma'];
					$sql = "SELECT ID, NOMBRE, ACTIVA,
						(SELECT NOMBRE FROM PAIS WHERE PAIS.ID_PAIS = EMPRESA.ID_PAIS AND ID_IDIOMA=$idioma) as PAIS,
						(SELECT NOMBRE FROM REGION WHERE REGION.ID_REGION = EMPRESA.ID_REGION AND ID_IDIOMA=$idioma) as REGION,
						(SELECT NOMBRE FROM PROVINCIA WHERE PROVINCIA.ID_PROVINCIA = EMPRESA.ID_PROVINCIA AND ID_IDIOMA=$idioma) as PROVINCIA,
						(SELECT NOMBRE FROM CIUDAD WHERE CIUDAD.ID_CIUDAD = EMPRESA.ID_CIUDAD AND ID_IDIOMA=$idioma) as CIUDAD,
						(SELECT NOMBRE FROM DISTRITO WHERE DISTRITO.ID_DISTRITO = EMPRESA.ID_DISTRITO AND ID_IDIOMA=$idioma) as DISTRITO,
						(SELECT NOMBRE FROM BARRIO WHERE BARRIO.ID_BARRIO = EMPRESA.ID_BARRIO AND ID_IDIOMA=$idioma) as BARRIO,
						(SELECT NOMBRE FROM CALLE WHERE CALLE.ID = EMPRESA.ID_CALLE) as CALLE,
						(SELECT NOMBRE FROM NUMERO_CALLE WHERE NUMERO_CALLE.ID = EMPRESA.ID_NUMERO_CALLE) as NUMERO_CALLE,
						ID_ACTIVIDAD, ID_GRUPO_ACTIVIDAD FROM EMPRESA WHERE ID = ?";	
					
					$stmt = $conn->prepare("$sql");
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$result = $stmt->get_result();	
					while ($row = $result->fetch_assoc()){
						$empresa = $row;
					}	
					$stmt->close();
				}
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
	$("#id_grupo_act").change(function() {
 		tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
		$.ajax({
			url: "get_actividad.php",
			type: 'post',
			data: { id_grupo_actividad: $('select[id=id_grupo_act]').val() },
				success: function(response){
					var myObj = JSON.parse(response);
					var html = "";
					for(i=0; i<myObj.length; i++){
						html += '<option value="' + myObj[i]['value'] + '">' + myObj[i]['label'] + '</option>';		
					}

					$("#id_act").html(html);
					tb_remove();				
				},				
   				error: function (request, status, error) {
        			//alert(request.responseText);
        			alert("Error");
					tb_remove();
    		}			
		});
	});


	$("#id_desac").click(function() {
		var estado = <?php echo $empresa['ACTIVA']; ?>;
		var id = <?php echo $id; ?>;	
		var activa = 0;
		if ( estado == 0 ) {
			activa = 1;
		}
		tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
		$.ajax({
			url: "desactivar_empresa.php",
			type: 'post',
			data: { activa: activa, id: id },
				success: function(response){
					tb_remove();
					location.reload();			
				},				
   				error: function (request, status, error) {
        			//alert(request.responseText);
        			alert("Error");
					tb_remove();
    		}			
		});	
		
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
			<img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png">
		</header>
		<div class="container_web">
			<div id="resultado" class="panel">
				<?php
					$clase_empresa = "texto_empresa";
					$texto_activar = "Desactivar";
					if ($empresa['ACTIVA'] == 0) {
						$clase_empresa = "texto_empresa-inactiva";
						$texto_activar = "Activar"; 
					}

					$html = "<div class='contenedor_empresa'>     		
							  	<span class='$clase_empresa' onclick='' lang='es'>
								" . $empresa['NOMBRE'] . "
								</span>				
							</div><span class='text-estandar'>
								Direccion:" .  $empresa['CALLE'] . " " . $empresa['NUMERO_CALLE'] . ", " . $empresa['CIUDAD'] . ", " . $empresa['REGION'] . ", " . $empresa['PAIS'] . "
								</span>";
					echo $html;		
				?>
				<br><br><br>
				<button id="id_desac" class="btn btn-outline-secondary"><?php echo $texto_activar; ?></button>	
				<br><br>			
				<label for="" class='text-bold'>Grupo Actividad:</label>
				<select id="id_grupo_act">
					<?php
					  $result = $conn -> query ("SELECT * FROM GRUPO_ACTIVIDAD WHERE ID_IDIOMA=$idioma");
					  while ($valores = mysqli_fetch_array($result)) {
					  	if ($valores['ID_GRUPO_ACTIVIDAD'] == $empresa['ID_GRUPO_ACTIVIDAD']) {
					    	echo '<option value="'.$valores['ID_GRUPO_ACTIVIDAD'].'" selected=\'selected\'>'.$valores['NOMBRE'].'</option>';
					    }
					    else {
					    	echo '<option value="'.$valores['ID_GRUPO_ACTIVIDAD'].'">'.$valores['NOMBRE'].'</option>';
					    }
					  }
					?>
				</select>
				<br>
				<br>
				<label for="" class='text-bold'>Actividad:</label>
				<select id="id_act">
					<?php
					  $result = $conn -> query ("SELECT * FROM ACTIVIDAD WHERE ID_IDIOMA=$idioma");
					  while ($valores = mysqli_fetch_array($result)) {
					  	if ($valores['ID_ACTIVIDAD'] == $empresa['ID_ACTIVIDAD']) {
					    	echo '<option value="'.$valores['ID_ACTIVIDAD'].' " selected=\'selected\'>'.$valores['NOMBRE'].'</option>';
					    }
					    else {
					    	echo '<option value="'.$valores['ID_ACTIVIDAD'].'">'.$valores['NOMBRE'].'</option>';
					    }
					  }
					?>
				</select>
				<br><br>

				<span>Nombre:</span><input type="text" class="form-control" id="id_variable" value = "<?php echo $empresa['NOMBRE']; ?>">
			    <br>
			    <button id="id_guardar" class="btn btn-outline-secondary">Guardar cambios</button>
			</div>
			
		</div>	
					
	</div>
	<footer>
	<p>©2018 LA SALLE OPEN UNIVERSITY</p>
	</footer>
</body>
</html>
