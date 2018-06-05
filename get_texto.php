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
				try {
					$stmt = $conn->prepare("SELECT * FROM IDIOMA");
					$stmt->execute();
					$result = $stmt->get_result();				
					$idiomas = array();
					$nombre_idioma = array();
					while ($row = $result->fetch_assoc()) {
						$idiomas[] = $row['ID'];
						$nombre_idioma[] = $row['NOMBRE'];
					}
					$stmt->close();
					$longitud = count($idiomas);
					if($longitud > 0) {
						$query = "";
						$app = $_POST['app'];
						$tabla = "<table class='table'>
						<thead>
						<tr>";
						$tabla .= "<td>VARIABLE</td>";
						for($i=0; $i<$longitud; $i++) {
							$tabla .= "<td>" . $nombre_idioma[$i] . "</td>";																
						}
						$tabla .= "</thead>";
						$query = "SELECT * FROM VARIABLE_TEXTO WHERE ID_APLICACION = ?";
						$stmt = $conn->prepare("$query");
						$stmt->bind_param("i", $app);
						$stmt->execute();
						$result = $stmt->get_result();	
						while ($row = $result->fetch_assoc()) {
							$tabla .= "<tr><td>" . $row['VARIABLE'] . "</td>";
							for($i=0; $i<$longitud; $i++) {
								$sql_insert = "SELECT * FROM TEXTO WHERE ID_VARIABLE = ? AND ID_IDIOMA  = ?";
								$stmt2 = $conn->prepare("$sql_insert");
								$stmt2->bind_param("ii", $row['ID'], $idiomas[$i]);
								$stmt2->execute();
								$result2 = $stmt2->get_result();	
								while ($row2 = $result2->fetch_assoc()) {
									$tabla .= "<td><input type='text' class='form-control' id='id_text_" . $row2['ID'] . "' value = '" . $row2['TEXTO'] . "'><button class='btn btn-outline-secondary' onclick='actualizar_text(" . $row2['ID'] . ", " . $idiomas[$i] . ")' >Guardar</button></td>";
								}
								$stmt2->close();																
							}
							$tabla .= "</tr>";
						}
						$tabla .= "</table>";
						$stmt->close();
						echo $tabla;
						exit;
					}
					else {
						echo "ERROR BBDD";
						exit;
					}
				} 
				catch(Exception $e) {
					echo "Exception - $e\n";
				}					
			}
			else {
				session_destroy();
				header("location:index.php?error=ataqueataque"); 
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
	exit;
?>