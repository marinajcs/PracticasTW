<?php
/*
  Funciones para gestionar el backup de la BBDD desde la aplicación PHP.
*/

/* Backup de la BBDD completa */
/**
 * Realiza una copia de seguridad de la base de datos.
 */
function DB_backup($db) {
  // Obtener listado de tablas
  $tablas = array();
  $result = mysqli_query($db,'SHOW TABLES');
  while ($row = mysqli_fetch_row($result))
    $tablas[] = $row[0];
  
  // Salvar cada tabla
  $salida = '';
  foreach ($tablas as $tab) {
    $result = mysqli_query($db,'SELECT * FROM '.$tab);
    $num = mysqli_num_fields($result);
    $meta = mysqli_fetch_fields($result);
    
    $salida .= 'DROP TABLE IF EXISTS '.$tab.';';
    $row2 = mysqli_fetch_row(mysqli_query($db,'SHOW CREATE TABLE '.$tab));
    $salida .= "\n\n".$row2[1].";\n\n";
    
    while ($row = mysqli_fetch_row($result)) {
      $salida .= 'INSERT INTO '.$tab.' VALUES(';
      for ($j=0; $j < $num; $j++) {
        if (!is_null($row[$j])) {
          if ($meta[$j]->type == MYSQLI_TYPE_BLOB) {
            // Tratar el atributo de imagen como datos binarios
            $row[$j] = mysqli_real_escape_string($db, $row[$j]);
            $salida .= '0x' . bin2hex($row[$j]);
          } else {
            // Tratar otros atributos como cadenas
            $row[$j] = addslashes($row[$j]);
            $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
            if (isset($row[$j]))
              $salida .= '"' . $row[$j] . '"';
            else
              $salida .= '""';
          }
        } else
          $salida .= 'NULL';
        if ($j < ($num-1))
          $salida .= ',';
      }
      $salida .= ");\n";
    }
    $salida .= "\n\n\n";
  }
  return $salida;
}

/* Restauración de la BBDD completa */
/**
 * Restaura la base de datos desde un archivo.
 */
function DB_restore($db, $f)
{
  mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=0');
  DB_delete($db);
  $error = [];
  $sql = file_get_contents($f);
  $queries = explode(';', $sql);
  foreach ($queries as $q) {
    $q = trim($q);
    if ($q != '' and !mysqli_query($db, $q)){
      print_r(mysqli_error($db));
      $error .= mysqli_error($db);
    }
  }
  mysqli_commit($db);
  mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');
  return $error;
}

/* Borrar el contenido de las tablas de la BBDD */
/**
 * Elimina el contenido de todas las tablas de la base de datos.
 */
function DB_delete($db)
{
  $result = mysqli_query($db, 'SHOW TABLES');
  while ($row = mysqli_fetch_row($result)){
      if ($row[0]=='Usuarios'){
        mysqli_query($db,"DELETE FROM Usuarios where email!='{$_SESSION['usuario']}'");
    }
    else{
        mysqli_query($db,'DELETE FROM '.$row[0]);
    }
  }
  mysqli_commit($db);
}

?>