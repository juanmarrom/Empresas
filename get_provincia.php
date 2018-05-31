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
				$id_region = $_POST['id_region'];
				$query = "SELECT * FROM PROVINCIA WHERE NOMBRE like'%".$search."%' AND ID_IDIOMA = " . $_SESSION['idioma'] . " AND ID_REGION = " . $id_region . "";
				$result = mysqli_query($conn,$query);
		
				$response = array();
				while($row = mysqli_fetch_array($result) ){
					$response[] = array("value"=>$row['ID_PROVINCIA'],"label"=>$row['NOMBRE']);
				}
				echo json_encode($response);
			}
		}
	}
	exit;
?>
