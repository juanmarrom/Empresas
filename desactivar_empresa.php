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
					$activa = $_POST['activa'];
					if (is_numeric($id)) {
						$sql_update = "UPDATE EMPRESA SET ACTIVA = ? WHERE ID = ?;";						
						$stmt2 = $conn->prepare("$sql_update");
						$stmt2->bind_param("ii", $activa,$id);
						$stmt2->execute();
						$stmt2->close();

						$viejo = 0;
						$campo = 'ACTIVA';
						if ($activa == 0) {
							$viejo = 1;
						} 

						$sql_insert = "INSERT INTO AUDITORIA_CAMBIO_EMPRESA (ID_EMPRESA, CAMPO, VIEJO, NUEVO) VALUES (?,?,?,?);";
						$stmt2 = $conn->prepare("$sql_insert");
						$stmt2->bind_param("isss", $id, $campo, $viejo, $activa);
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