<?php

/**
 * Obtiene todas las incidencias de la base de datos.
 */
function getIncidenciasBD($db)
{
  $res = mysqli_query($db, "SELECT titulo, descripcion, lugar, email, fecha_hora, palabras_clave, estado, likes, dislikes, imagenes FROM incidencia ORDER BY fecha_hora");
  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      $tabla = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else
      $tabla = [];
    mysqli_free_result($res);
  } else {
    $tabla = false;
  }
  return $tabla;
}

/**
 * Inserta una incidencia en la base de datos.
 */
function insertarIncidenciaBD($valores, $imgs, $db)
{
  $titulo = addslashes($valores['nombre']);
  $descripcion = addslashes($valores['descripcion']);
  $lugar = addslashes($valores['lugar']);
  $email = $_SESSION['email'];
  $timeQuery = mysqli_query($db, "SELECT CURRENT_TIMESTAMP");
  if ($timeQuery) {
    $row = mysqli_fetch_row($timeQuery);
    $curr_time = $row[0];
  }
  $fecha_hora = addslashes($curr_time);
  $palabras_clave = addslashes($valores['clave']);

  //titulo, descripcion, lugar, email, fecha_hora, palabras_clave, estado, likes, dislikes
  $query1 = "INSERT INTO incidencia (titulo, descripcion, lugar, email, fecha_hora, palabras_clave) 
             VALUES ('$titulo', '$descripcion', '$lugar', '$email', '$fecha_hora', '$palabras_clave')";
  $res1 = mysqli_query($db, $query1);
}

/**
 * Inserta un usuario en la base de datos.
 */
function insertarUsuarioBD($valores, $db)
{
  $email = addslashes($valores['email']);
  $telefono = addslashes($valores['telefono']);
  $nombre = addslashes($valores['nombre']);
  $apellidos = addslashes($valores['apellidos']);
  $direccion = addslashes($valores['direccion']);
  $clave = addslashes($valores['password']);
  
  $query1 = "INSERT INTO usuarios (email, telefono, nombre, apellidos, direccion, clave) 
             VALUES ('$email', '$telefono', '$nombre', '$apellidos', '$direccion', '$clave')";
  
  $res1 = mysqli_query($db, $query1);  
}

/**
 * Elimina una incidencia de la base de datos.
 */
function eliminarIncidencia($ID_Incidencia, $db)
{
  $res = mysqli_query($db, "DELETE FROM incidencia WHERE ID_Incidencia='$ID_Incidencia'");

  if (!$res) {
    $info[] = 'Error en la consulta ' . __FUNCTION__;
    $info[] = mysqli_error($db);
  }

  if (isset($info))
    return $info;
  else
    return true;
}

/**
 * Obtiene los datos de un usuario de la base de datos.
 */
function cogerDatos($idUser, $db){
  $query = "SELECT * FROM usuarios WHERE id = $idUser";
  $res1 = mysqli_query($db, $query);
}

/**
 * Obtiene el número de usuarios en la base de datos.
 */
function getNumUsuariosBD($db, $cadenab = '')
{
  if ($cadenab != '')
    $cadenab .= ' AND ';
  $res = mysqli_query($db, "SELECT COUNT(*) FROM usuarios");
  $num = mysqli_fetch_row($res)[0];
  mysqli_free_result($res);
  return $num;
}

/**
 * Obtiene el número de incidencias en la base de datos.
 */
function getNumIncidenciasBD($db, $cadenab = '')
{
  if ($cadenab != '')
    $cadenab .= ' AND ';
  $res = mysqli_query($db, "SELECT COUNT(*) FROM incidencia");
  $num = mysqli_fetch_row($res)[0];
  mysqli_free_result($res);
  return $num;
}

/**
 * Obtiene los usuarios de la base de datos.
 */
function get_UsuariosBD($db, $primero = 0, $numitems = 0)
{
  $tabla = array();
  if ($numitems <= 0) { // Listarlos todos
    $rango = '';
  } else {
    $rango = 'LIMIT ' . (int) ($numitems) . ' OFFSET ' . abs($primero);
  }

  // Consulta a la BBDD
  $res = mysqli_query(
    $db,
    "SELECT icono, nombre, apellidos, email, telefono, direccion, estado FROM usuarios ORDER BY nombre $rango"
  );

  if ($res) {
    // Si no hay error
    if (mysqli_num_rows($res) > 0) {
      // Si hay alguna tupla de respuesta
      $tabla = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
      $tabla = [];
    }
    // Liberar memoria de la consulta
    mysqli_free_result($res);
  } else {
    $tabla = false;
  }

  return $tabla;
}

/**
 * Obtiene las incidencias de la base de datos.
 */
function get_IncidenciasBD($db, $opcion, $primero = 0, $numitems = 0, $filtros)
{
  $tabla = array();
  if ($numitems <= 0) { // Listarlos todos
    $rango = '';
  } else {
    $rango = 'LIMIT ' . (int) ($numitems) . ' OFFSET ' . abs($primero);
  }
  $orden = '';
  $lugar = strtolower($filtros['buscar_lugar']);
  $texto = strtolower($filtros['buscar_texto']);
  $estados = $filtros['estados'];
  $no_where = false;
  $query = "";

  if ($opcion == "ver_incidencias"){
    if ($texto != '' and $lugar != ''){
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE LOWER(descripcion) LIKE '%$texto%' and LOWER(lugar) LIKE '%$lugar%'";
    } else if ($texto != '' and $lugar == ''){
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE LOWER(descripcion) LIKE '%$texto%'";
    } else if ($texto == '' and $lugar != ''){
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE LOWER(lugar) LIKE '%$lugar%'";
    } else {
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia";
      $no_where = true;
    }
  } else {
    $usuario = $_SESSION['email'];
    if ($texto != '' and $lugar != ''){
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE email = '$usuario' and LOWER(descripcion) LIKE '%$texto%' and LOWER(lugar) LIKE '%$lugar%'";
    } else if ($texto != '' and $lugar == ''){
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE email = '$usuario' and LOWER(descripcion) LIKE '%$texto%'";
    } else if ($texto == '' and $lugar != ''){
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE email = '$usuario' and LOWER(lugar) LIKE '%$lugar%'";
    } else {
      $query = "SELECT ID_Incidencia, titulo, descripcion, lugar, fecha_hora, email, palabras_clave, likes, dislikes, estado 
                  FROM incidencia WHERE email = '$usuario'";
    }
  }

  if (isset($estados) and !empty($estados)){
    if ($no_where){
      $query .= " WHERE (";
    } else {
      $query .= " and (";
    }
    
    foreach ($estados as $est){
      $query .= "estado = '$est' or ";
    }
    $query = substr($query, 0, -4);
    $query .= ")";
  }

  if ($filtros['orden'] == "recientes"){
    $orden = 'fecha_hora';
  } else if ($filtros['orden'] == "likes_desc"){
    $orden = 'likes';
  } else {
    $orden = 'dislikes';
  }

  $query2 = $query;
  $query2 .= " ORDER BY $orden DESC";
  $query .= " ORDER BY $orden DESC $rango";
  # echo $query; # comprobación de query
  
  $res = mysqli_query($db, $query);
  $res2 = mysqli_query($db, $query2);
  $nfilas = 0;
  if ($res && $res2) {
    if (mysqli_num_rows($res) > 0 && mysqli_num_rows($res2)) {
      $tabla = mysqli_fetch_all($res, MYSQLI_ASSOC);
      $tabla2 =  mysqli_fetch_all($res2, MYSQLI_ASSOC);
      $nfilas = count($tabla2);
    } else {
      $tabla = [];
      $tabla2 = [];
    }
    mysqli_free_result($res);

  } else {
    $tabla = false;
    $tabla2 = false;
  }

  return ['tabla' => $tabla, 'nincid' => $nfilas];
}

/**
 * Obtiene las imágenes relacionadas con las incidencias de la base de datos.
 */
function get_ImagesBD($db, $incid, $primero = 0, $numitems = 0)
{

  if ($numitems <= 0) { // Listarlos todos
    $rango = '';
  } else {
    $rango = 'LIMIT ' . (int) ($numitems) . ' OFFSET ' . abs($primero);
  }

  $tabla = array();

  // Consulta a la BBDD
  foreach ($incid as $inc) {
    $id_inc = $inc["ID_Incidencia"];
    $res = mysqli_query($db, "SELECT * FROM imagen WHERE ID_Incidencia = $id_inc");

    if ($res) {
      while ($row = mysqli_fetch_assoc($res)) {
        $imagen = $row["imagen"];
        $tabla[$inc["ID_Incidencia"]][] = $imagen;
      }
      mysqli_free_result($res);
    } else {
      $tabla[$inc["ID_Incidencia"]][] = '';
    }
  }

  return $tabla;
}

/**
 * Inserta un registro de log en la base de datos.
 */
function insertLog($mensaje, $db)
{
  $date = date('Y-m-d H:i:s');
  $res = mysqli_query($db, "INSERT INTO log (fecha, descripcion) VALUES ('{$date}', '{$mensaje}')");

  if (!$res) {
    $info[] = 'Error en la consulta ' . __FUNCTION__;
    $info[] = mysqli_error($db);
  }


  if (isset($info))
    return $info;
  else
    return true;
}

/**
 * Obtiene el número total de registros de logs en la base de datos.
 */
function getNumLogsBD($db)
{
  $res = mysqli_query($db, "SELECT COUNT(*) FROM log");
  $num = mysqli_fetch_row($res)[0];
  mysqli_free_result($res);
  return $num;
}

/**
 * Obtiene los registros de logs de la base de datos.
 */
function get_LogsBD($db, $primero = 0, $numitems = 0)
{
  if ($numitems <= 0) {
    $rango = '';
  } else {
    $rango = 'LIMIT ' . (int) ($numitems) . ' OFFSET ' . abs($primero);
  }

  $res = mysqli_query($db, "SELECT fecha, descripcion FROM log ORDER BY fecha DESC $rango");

  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      $tabla = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
      $tabla = [];
    }
    mysqli_free_result($res);
  } else {
    $tabla = false;
  }

  return $tabla;
}

/**
 * Genera una tabla HTML con el listado de logs.
 */
function listadoLogsHTML($logs)
{
  $html = '<table>';
  $html .= '<tr style="background-color: rgb(229, 198, 94);"><th>Fecha</th><th>Descripción</th></tr>';

  $fila = 0;

  foreach ($logs as $log) {
    $fecha = $log['fecha'];
    $descripcion = $log['descripcion'];

    // Aplica diferentes colores a las filas pares e impares
    $color = ($fila % 2 == 0) ? 'rgb(229, 198, 94)' : 'rgb(235, 217, 160)';

    $html .= "<tr style='background-color: $color;'><td style='color: blue;'>$fecha</td><td>$descripcion</td></tr>";

    $fila++; // Incrementa la variable de fila en cada iteración
  }

  $html .= '</table>';

  return $html;
}

/**
 * Obtiene el ranking de las personas con más incidencias registradas.
 */
function get_RankingPersonas($db) {
  $query = "SELECT email, COUNT(*) as total_incidencias FROM incidencia GROUP BY email ORDER BY total_incidencias DESC LIMIT 3";
  
  $res = mysqli_query($db, $query);

  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      $ranking = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
      $ranking = [];
    }
    mysqli_free_result($res);
  } else {
    $ranking = false;
  }

  return $ranking;
}


?>