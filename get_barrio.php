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
				$query = "SELECT * FROM BARRIO WHERE NOMBRE like'%".$search."%' AND ID_IDIOMA = " . $_SESSION['idioma'] . "";
				$result = mysqli_query($conn,$query);
		
				$response = array();
				while($row = mysqli_fetch_array($result) ){
					$response[] = array("value"=>$row['ID_BARRIO'],"label"=>$row['NOMBRE']);
				}
				echo json_encode($response);
			}
		}
	}
	exit;
?>
