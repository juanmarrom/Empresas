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
					$activo = $_POST['activo'];
					if (is_numeric($id)) {
						$sql_update = "UPDATE USUARIO SET ACTIVO = ? WHERE ID = ?;";						
						$stmt2 = $conn->prepare("$sql_update");
						$stmt2->bind_param("ii", $activo,$id);
						$stmt2->execute();
						$stmt2->close();

						$viejo = 0;
						$campo = 'ACTIVO';
						if ($activo == 0) {
							$viejo = 1;
						} 

						$sql_insert = "INSERT INTO AUDITORIA_CAMBIO_USUARIO  (ID_USUARIO, CAMPO, VIEJO, NUEVO) VALUES (?,?,?,?);";
						$stmt2 = $conn->prepare("$sql_insert");
						$stmt2->bind_param("isss", $id, $campo, $viejo, $activo);
						$stmt2->execute();
						$stmt2->close();						

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