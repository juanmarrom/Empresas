<?php
 class Util {
    private static $conn = "";

    public static function iniciarConexion($ruta_fichero) {
		$numero_linea = 1;
		$fp = fopen($ruta_fichero, "r");
		$url = "localhost";
		$login = "root";
		$password = "";
		$base_datos = "BD_EMPRESAS";

		while(!feof($fp)) {
			$linea = fgets($fp);
			//echo $linea . "\n";
			if ($numero_linea < 5) {
				list($variable, $valor) = explode('=', $linea);
				$valor = trim($valor);
				//echo "Variable: $variable; Valor: '$valor'\n";
				if ($numero_linea == 1) {
					$url = $valor;
				}
				if ($numero_linea == 2) {
					$login = $valor;
				}
				if ($numero_linea == 3) {
					$password = $valor;
				}
				if ($numero_linea == 4) {
					$base_datos = $valor;
				}
				$numero_linea++;
			}
		}
		fclose($fp);
		self::$conn = new mysqli("$url", "$login", "$password", "$base_datos");
		self::$conn->query("SET NAMES utf8");
		return self::$conn;
    } 

    public static function getConexion(){
	return self::$conn;
    } 

    public static function cerrarConexion(){
	self::$conn->close();
    } 

    public static function getFecha(){
       $anio = date('Y');
       $mes = date('m');
       $dia = date('d');
       return $dia . '/' . $mes . '/' . $anio;
    }
 
    public static function getHora(){
       $hora = date('H');
       $minutos = date('i');
       $segundos = date('s');
       return $hora . ':' . $minutos . ':' . $segundos;
    }


 } 
?>
