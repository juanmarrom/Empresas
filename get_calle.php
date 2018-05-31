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
				$id_barrio = $_POST['id_barrio'];
				$query = "SELECT * FROM CALLE WHERE NOMBRE like'%".$search."%'" . " AND ID_BARRIO = " . $id_barrio . "";
				$result = mysqli_query($conn,$query);
		
				$response = array();
				while($row = mysqli_fetch_array($result) ){
					$response[] = array("value"=>$row['ID'],"label"=>$row['NOMBRE']);
				}
				echo json_encode($response);
			}
		}
	}
	exit;
?>
