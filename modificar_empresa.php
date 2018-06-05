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
					$id = $_POST['id'];
					if (is_numeric($id)) {
						$empresa;
						$sql = "SELECT ID, NOMBRE, ID_ACTIVIDAD, ID_GRUPO_ACTIVIDAD FROM EMPRESA WHERE ID = ?";	
						$stmt = $conn->prepare("$sql");
						$stmt->bind_param("i", $id);
						$stmt->execute();
						$result = $stmt->get_result();	
						while ($row = $result->fetch_assoc()){
							$empresa = $row;
						}

						$sql_update = "UPDATE EMPRESA SET NOMBRE = ?, ID_ACTIVIDAD = ?, ID_GRUPO_ACTIVIDAD = ? WHERE ID = ?;";		
						$stmt2 = $conn->prepare("$sql_update");
						$stmt2->bind_param("siii", $_POST['nombre'], $_POST['id_actividad'], $_POST['id_grupo_actividad'], $id);
						$stmt2->execute();
						$stmt2->close();

						foreach($_POST as $nombre_campo => $valor){ 
							if ($nombre_campo != "id") {
								if ($empresa[strtoupper($nombre_campo)] != $valor) {
									$sql_insert = "INSERT INTO AUDITORIA_CAMBIO_EMPRESA (ID_EMPRESA, CAMPO, VIEJO, NUEVO) VALUES (?,?,?,?);";
									$stmt2 = $conn->prepare("$sql_insert");
									$stmt2->bind_param("isss", $id, $nombre_campo, $empresa[$nombre_campo], $valor);
									$stmt2->execute();
									$stmt2->close();
								}		

							}
						}	
					}
					else {
						session_destroy();
						header("location:index.php?error=ataque"); 
					}
					echo "OK";
				} 
				catch(Exception $e) {
					echo "Exception - $e\n";
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
	exit;
?>