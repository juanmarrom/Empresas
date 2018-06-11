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
<link rel="shortcut icon" href="http://opencampus.uols.org/theme/image.php/lasalle1314/theme/1464558442/favicon">

<script>

$(document).ready(function(){
	tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
	$("#id_mostrar").click(function() {
		//alert($('select[id=id_tipo]').val());
		//alert($('select[id=id_user]').val());
		
		tb_show("", "loading_2.html?keepThis=true&TBiframe=true&align=center&height=600&width=800&modal=true", false);
		$.ajax({
			url: "get_logs.php",
			type: 'post',
			data: { tipo: $('select[id=id_tipo]').val(), user: $('select[id=id_user]').val()  },
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
		
	});

	tb_remove();
});
	
</script>

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
				<label class='text-bold' for="">Seleccione el usuario:</label>
				<select id="id_user">
					<option value="0">Todos</option>
					<?php 
					  $result = $conn -> query ("SELECT * FROM USUARIO");
					  while ($valores = mysqli_fetch_array($result)) {
					  	echo '<option value="'. $valores['ID'] .'">' . $valores['LOGIN'] . '</option>';
					  }
					?>
				</select>
				<br>
				<br>

				<label class='text-bold' for="" class='text-bold'>Seleccione el tipo de Log:</label>
				<select id="id_tipo">
					<option value="0">Login</option>
					<option value="1">Busqueda</option>
				</select>
			    <br><br>
			    <button id="id_mostrar" class="btn btn-outline-secondary">Mostrar</button>
			     		
			</div>
			<div id="resultado" class="panel">
				<label for="" class='text-bold'>Logs:</label>
			</div>
			
		</div>
					
	</div>
	<footer>
	<p>©2018 LA SALLE OPEN UNIVERSITY</p>
	</footer>
</body>
</html>
