<?php
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			if(isset($_POST['search'])){
				try {
					$search = $_POST['search'];
					$id_ciudad = $_POST['id_ciudad'];
					$query = "SELECT MAX(ID) as ID, NOMBRE FROM MERCADO WHERE NOMBRE LIKE ? AND ID_CIUDAD = ? GROUP BY NOMBRE";
					$stmt = $conn->prepare("$query");
					$nom = "%" . $search . "%";
					$stmt->bind_param("si", $nom, $id_ciudad);
					$stmt->execute();
					$result = $stmt->get_result();				
					$response = array();
					while ($row = $result->fetch_assoc()) {
						$response[] = array("value"=>$row['ID'],"label"=>$row['NOMBRE']);
					}
					$stmt->close();
					echo json_encode($response);
				} 
				catch(Exception $e) {
					echo "Exception - $e\n";
				}					
			}
		}
		else {
			echo "Session Fallo 1";
		}		
	}
	else {
		echo "Session Fallo 1";
	}
?>