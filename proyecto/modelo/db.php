<?php

require('credenciales.php');

// Conexión a la BBDD
// Devuelve un resource si hay éxito o una cadena con una descripción del error
/**
 * Establece una conexión con la base de datos.
 */
function DB_conexion() {
  $db = mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_DATABASE);
  if (!$db)
    return "Error de conexión a la base de datos (".mysqli_connect_errno().") : ".mysqli_connect_error();

  // Establecer el conjunto de caracteres del cliente
  mysqli_set_charset($db,"utf8");

  return $db;
}

// Desconexión de la BBDD
/**
 * Cierra la conexión con la base de datos.
 */
function DB_desconexion($db) {
  mysqli_close($db);
}

?>