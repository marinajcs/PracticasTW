<?php
/*
  Controlador para restaurar la BBDD.
*/

/**
 * Valida y procesa la restauración de la base de datos.
 */
function validarRestore()
{
  $r = '';
  if (isset($_POST['submit'])) {
    /* Comprobar que se ha subido algún fichero */
    if ((sizeof($_FILES) == 0) || !array_key_exists("fichero", $_FILES))
      $error = "No se ha podido subir el fichero";
    else if (!is_uploaded_file($_FILES['fichero']['tmp_name']))
      $error = "Fichero no subido. Código de error: " . $_FILES['fichero']['error'];
    else {
      $db = DB_conexion();
      $error = DB_restore($db, $_FILES['fichero']['tmp_name']);
      DB_desconexion($db);
    }
    if (isset($error))
      $r .= $error;
    else{
      $mensaje = "El administrador " . $_SESSION['email'] . " ha RESTAURADO la base de datos con éxito";
      insertLog($mensaje, $db);
      $r .= "Base de datos restaurada correctamente";
    }
  } else {
    $r .= View_restore();
  }
  return $r;
}

?>