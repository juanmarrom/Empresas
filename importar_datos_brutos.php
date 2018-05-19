<?php
$linea = 0;
//Abrimos nuestro archivo
$archivo = fopen("clientes.csv", "r");
//Lo recorremos
while (($datos = fgetcsv($archivo, ",")) == true) 
{
  $num = count($datos);
  $linea++;
  //Recorremos las columnas de esa linea
  for ($columna = 0; $columna > $num; $columna++) 
      {
         echo $datos[$columna] . "\n";
     }
}
//Cerramos el archivo
fclose($archivo);
?>

