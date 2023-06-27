<?php
/*
  Controlador para borrar la BBDD.
*/

//require('modelo/db_backup.php'); // Operaciones de backup de la BBDD
//require('vista/form_restore.php');
//require('vista/html.php');

/**
 * Se conecta a la base de datos, borra todas las tablas e inserta un log de ello.
 */
function deleteBaseDatos()
{
  if (isset($_POST['borrar'])) {
    if (!is_string($db = DB_conexion())) {

      //$db = DB_conexion();
      $mensaje = "El administrador " . $_SESSION['email'] . " ha BORRADO todas las TABLAS de la base de datos correctamente";
      insertLog($mensaje, $db);

      echo DB_delete($db);
      DB_desconexion($db);
      borradoTablasCorrecto();
    }
  } else {
    return <<<HTML
  <form method="post" action="">
    <button type="submit" name="borrar" onclick="return confirm('¿Estás seguro de que deseas borrar la base de datos? Esta acción no se puede deshacer.');">Pulsa aquí para borrar la base de datos</button>
  </form>
HTML;
  }

}

?>