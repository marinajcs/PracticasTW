<?php

/*
  Controlador para hacer un backup de la BBDD.
*/

require('modelo/db_backup.php'); // Operaciones de backup de la BBDD

/**
 * Verifica si se ha establecido el parámetro 'backup' en la solicitud POST. Si está establecido, realiza una copia de seguridad de la base de datos e inicia la descarga del archivo de copia de seguridad.
 * Si no está establecido, muestra un formulario para permitir al usuario iniciar la descarga de la copia de seguridad.
 */
function checkBackup()
{
  if (isset($_POST['backup'])) {
    if (!is_string($db = DB_conexion())) {
      $mensaje = "El administrador " . $_SESSION['email'] . " ha realizado un BACKUP con éxito";
      insertLog($mensaje, $db);
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="db_backup.sql"');
      echo DB_backup($db);
      DB_desconexion($db);
    }
  } else {
    return <<<HTML
    <form method="post" action="">
    <button type="submit" name="backup">Pulsa aquí para descargar backup</button>
    </form>
  HTML;
  }
}

?>