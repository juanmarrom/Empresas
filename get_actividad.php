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
					$id_grupo_actividad = $_POST['id_grupo_actividad'];
					$query = "SELECT ID_ACTIVIDAD, NOMBRE FROM ACTIVIDAD WHERE ID_GRUPO_ACTIVIDAD = ? AND ID_IDIOMA = ?";
					$stmt = $conn->prepare("$query");
					$nom = "%" . $search . "%";
					$stmt->bind_param("ii", $id_grupo_actividad, $_SESSION['idioma']);
					$stmt->execute();
					$result = $stmt->get_result();				
					$response = array();
					while ($row = $result->fetch_assoc()) {
						$response[] = array("value"=>$row['ID_ACTIVIDAD'],"label"=>$row['NOMBRE']);
					}
					$stmt->close();
					echo json_encode($response);
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