<?php
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			if(isset($_POST['search'])){
				$search = $_POST['search'];
				$id_pais = $_POST['id_pais'];
				$query = "SELECT * FROM REGION WHERE NOMBRE like ? AND ID_IDIOMA = " . $_SESSION['idioma'] . " AND ID_PAIS = ?";
				$stmt = $conn->prepare("$query");
				$nom = "%" . $search . "%";
				$stmt->bind_param("si", $nom, $id_pais);
				$stmt->execute();	
				$result = $stmt->get_result();			
				$response = array();
				while ($row = $result->fetch_assoc()) {
					$response[] = array("value"=>$row['ID_REGION'],"label"=>$row['NOMBRE']);
				}	
				$stmt->close();
				echo json_encode($response);
			}
		}
		else {
			echo "Session Fallo 1";
		}		
	}
	else {
		echo "Session Fallo 2";
	}
?>
