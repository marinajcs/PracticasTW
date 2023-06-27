<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Conversor</title>
  <style>
    .error {
      color: red;
    }

    p:nth-child(3), p:nth-child(11) {
      margin: 0;
    }

    p:nth-child(4) {
      margin-bottom: 0;
    }

    label:not(:first-of-type):after {
      content: '';
      display: block;
    }

    [type="submit"] {
      margin-top: 15px;
    }
  </style>
</head>

<!-- Ejemplo para ilustrar el funcionamiento de PHP para procesar los datos de un formulario con los pasos típicos de:
       - validación de entradas
       - recuperación de datos del formulario si hay errores (sticky)
       - procesar y mostrar resultados
     Nota: la modularización es mejorable
           no usar <style>, aquí se usa por simplicidad
-->

<body>
  <h1>Conversor de temperaturas</h1>

<?php

/* *************************** */
/* *** Inicio de la aplicación */

/* Obtener y validar parámetros */
$params = getParams($_GET);

if ($params['enviado']==true && 
    ($params['errcelsius']=='' && $params['errdestino']=='')) {
  /* Si se han recibido parámetros y son correctos */
  calcValues($params);
  showResults($params);
} else {
  /* Si no se han recibido parámetros o son incorrectos */
  showForm($params);
}

/* ************************ */
/* *** Fin de la aplicación */

?>

</body>
</html>


<?php

/* ************************ */
/* *** Funciones auxiliares */

/* Obtener y validar parámetros del formulario
   
   Entradas/argumentos:
     $get : array asociativo con datos de entrada (normalmente será $_GET o $_POST)
            $get['celsius'] : Temperatura de entrada en Celsius
            $get['destino'] : A qué unidad deseamos convertir: 'Fahrenheit', 'Kelvin', 'Rankine'
   
   Valor devuelto: array asociativo con:
     'enviado' : true si se han recibido datos de entrada (se envió el formulario)
     'Celsius' : valor de entrada (grados celsius)
     'errcelsius' : si la cadena está vacía -> el valor de entrada 'celsius' es correcto
                    si la cadena no está vacía -> mensaje descriptivo del error
     'errdestino' : si la cadena estña vacía -> el valor de entrada 'destino' es correcto
                    si la cadena no está vacía -> mensaje descriptivo del error
*/
function getParams($get) {
  if (isset($get['celsius'])) { /* El formulario ha sido enviado */
    $result['enviado'] = true;

    /* Comprobar valor de Celsius */
    $result['errcelsius'] = '';
    if (!isset($get['celsius']) or empty($get['celsius']))
      $result['errcelsius'] = 'No ha indicado ningún valor';
    else if (!is_numeric($get['celsius']))
      $result['errcelsius'] = 'El valor debe ser un número';
    else if ($get['celsius']<-100) 
      $result['errcelsius'] = 'El número ha de ser mayor que -100';
    $result['Celsius'] = $get['celsius'];
  
    /* Comprobar si hay alguna escala */
    $result['errdestino'] = '';
    if (!isset($get['destino'])) {
      $result['errdestino'] = 'Ha de seleccionar al menos una escala';
    } else {
      if (in_array('Fahrenheit',$get['destino']))
        $result['Fahrenheit'] = '0';
      if (in_array('Kelvin',$get['destino']))
        $result['Kelvin'] = '0';
      if (in_array('Rankine',$get['destino']))
        $result['Rankine'] = '0';
    }
  } else {  /* El formulario aun no ha sido enviado */
    $result['enviado'] = false;
    $result['Celsius'] = '';
  }

  return $result;
}

/* A partir de los valores enviados del formulario calcular las conversiones
  
   El argumento params se pasa por referencia: sirve para entradas y salidas
   Entradas: 'Celsius' contendrá el valor de entrada
             pueden existir las claves 'Fahrenheit', 'Kelvin', 'Rankine'
   Salidas: en caso de existir las claves, almacena en ellas la conversión correspondiente
*/
function calcValues(&$params) {
  if (array_key_exists('Fahrenheit',$params))
    $params['Fahrenheit'] = $params['Celsius']*9/5+32;
  if (array_key_exists('Kelvin',$params))
    $params['Kelvin'] = $params['Celsius']+273.15;
  if (array_key_exists('Rankine',$params))
    $params['Rankine'] = $params['Celsius']*9/5+491.67;
}

/* Mostrar resultados de conversiones 
   Genera código HTML con el resultado de las conversiones y un enlace para volver a calcular
*/
function showResults($params) {
  foreach (['Fahrenheit','Kelvin','Rankine'] as $v)
    if (array_key_exists($v,$params))
      echo "<p>Grados $v: $params[$v]</p>";
  echo "<p><a href='".$_SERVER["SCRIPT_NAME"]."'>Calcule otra conversión</a></p>";
}

/* Mostrar formulario (recuperando datos si es posible / sticky form) 
   Entradas: $params es un array asociativo con:
             'enviado': si vale false indica que no se ha recibido nada desde el formulario (primera consulta)
             'Celsius': Temperatura de entrada
             'errcelsius': Mensaje de error a mostrar junto a input de temperatura
             'errdestino': Mensaje de error a mostrar junto a selector de destinos
             'Fahrenheit': Valor convertido a Fahrenheit (opcional)
             'Kelvin': Valor convertido a Kelvin (opcional)
             'Rankine': Valor convertido a Rankine (opcional)
*/
function showForm($params) {
  if ($params['enviado']==false) {
    $params['Celsius'] = '';
    $params['errcelsius'] = '';
    $params['errdestino'] = '';
  }

  echo "<form action='".$_SERVER['SCRIPT_NAME']."' method='get'>
          <label for='cel'>Temperatura en Celsius:</label>
          <input type='text' id='cel' name='celsius' value='".$params['Celsius']."'/>";
  if ($params['errcelsius']!='') echo "<p class='error'>{$params['errcelsius']}</p>";

  echo "<p>A qué unidad desea convertir:</p>";

  echo "<input type='checkbox' id='dfah' name='destino[]' value='Fahrenheit'";
  if (array_key_exists('Fahrenheit',$params)) echo ' checked';
  echo ">";
  echo "<label for='dfah'>Fahrenheit</label>";

  echo "<input type='checkbox' id='dkel' name='destino[]' value='Kelvin'";
  if (array_key_exists('Kelvin',$params)) echo ' checked';
  echo ">";
  echo "<label for='dkel'>Kelvin</label>";

  echo "<input type='checkbox' id='dran' name='destino[]' value='Rankine'";
  if (array_key_exists('Rankine',$params)) echo ' checked';
  echo ">";
  echo "<label for='dran'>Rankine</label>";

  if ($params['errdestino']!='') echo "<p class='error'>{$params['errdestino']}</p>";

  echo "<input type='submit' value='Convertir'/>";
  echo "</form>";
}

?>
