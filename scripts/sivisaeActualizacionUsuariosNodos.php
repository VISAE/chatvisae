<?php

/*
  scripts para actualizar los usuarios registrados en los nodos
 * //Autor: Ing. Andres Camilo Mendez Aguirre
  //Fecha: 21/02/2017
 */

echo "entramos";

// Conectando, seleccionando la base de datos
$link = mysql_connect('192.168.4.24', 'root', 'V1s43_S3rv!d0r_Tw0')
    or die('No se pudo conectar: ' . mysql_error());
echo 'Connected successfully';
mysql_select_db('moodle') or die('No se pudo seleccionar la base de datos');

$query = "UPDATE moodle.mdl_user mu LEFT JOIN edu_users eu
                 ON eu.`usuario`=mu.username
                 SET mu.icq=eu.`codigo`
                 WHERE mu.icq='';";
				 
$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
// Se actualizan los que no cruzaron
$query=" UPDATE moodle.mdl_user mu SET mu.`icq`='NO' WHERE mu.`icq`='' ";
$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());