<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Conversor</title>
  <style>
    main {
      font-family: Arial;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    form {
      border: 2px solid lightgray;
      padding: 5px;
      display: inline-flex;
      align-items: center;
      background-color: lightblue;
    }

    fieldset {
      display: flex;
      flex-direction: column;
    }

    label {
      margin: 10px;
      display: flex;
      flex-direction: column;
    }

    .error {
      color: red;
    }
  </style>
</head>


<body>
  <main>
    <h1>Calculadora</h1>

    <?php

    /* *************************** */
    /* *** Inicio de la aplicación */

    /* Obtener y validar parámetros */
    $params = getParams($_GET);

    if (
      $params['enviado'] == true &&
      ($params['errn1'] == '' && $params['errn2'] == '' && $params['errop'] == '')
    ) {
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
  </main>
</body>

</html>


<?php
function getParams($get)
{
  //if (isset($get['numero1']) and isset($get['numero2'])) { /* El formulario ha sido enviado */
    $result['enviado'] = true;

    $result['errn1'] = '';
    if (!isset($get['numero1']) or empty($get['numero1']))
      $result['errn1'] = 'No ha indicado ningún valor para el primer dato';
    else if (!is_numeric($get['numero1']))
      $result['errn1'] = 'El primer dato debe ser un número';
    $result['Numero1'] = $get['numero1'];

    $result['errn2'] = '';
    if (!isset($get['numero2']) or empty($get['numero2']))
      $result['errn2'] = 'No ha indicado ningún valor para el segundo dato';
    else if (!is_numeric($get['numero2']))
      $result['errn2'] = 'El segundo dato debe ser un número';
    $result['Numero2'] = $get['numero2'];

    $result['errop'] = '';
    if (isset($get['suma']))
      $result['Suma'] = '0';
    else if (isset($get['resta']))
      $result['Resta'] = '0';
    else if (isset($get['producto']))
      $result['Producto'] = '0';
    else if (isset($get['division'])) {
      $result['Division'] = '0';
      if ($get['numero2'] == 0) {
        $result['errop'] = 'Operación definida (división por 0)';
      }
    }

  //} else { /* El formulario aun no ha sido enviado */
    $result['enviado'] = false;
    $result['n1'] = '';
    $result['n2'] = '';
  //}

  return $result;
}

/* A partir de los valores enviados del formulario calcular las conversiones
El argumento params se pasa por referencia: sirve para entradas y salidas
Entradas: 'Celsius' contendrá el valor de entrada
pueden existir las claves 'Fahrenheit', 'Kelvin', 'Rankine'
Salidas: en caso de existir las claves, almacena en ellas la conversión correspondiente
*/
function calcValues(&$params)
{
  if (array_key_exists('Suma', $params))
    $params['Suma'] = $params['Numero1']+$params['Numero2'];
  if (array_key_exists('Resta', $params))
    $params['Resta'] = $params['Numero1']-$params['Numero2'];
  if (array_key_exists('Producto', $params))
    $params['Producto'] = $params['Numero1']*$params['Numero2'];
  if (array_key_exists('Division', $params))
    $params['Division'] = $params['Numero1']/$params['Numero2'];

}

/* Mostrar resultados de conversiones 
Genera código HTML con el resultado de las conversiones y un enlace para volver a calcular
*/
function showResults($params)
{
  foreach (['Suma', 'Resta', 'Producto', 'Division'] as $v)
    if (array_key_exists($v, $params))
      echo "<p>Resultado $v: $params[$v]</p>";

  showForm($params);
}

function showForm($params)
{
  if ($params['enviado'] == false) {
    $params['Valor'] = '';
    $params['errn1'] = '';
    $params['errn2'] = '';
    $params['erroper'] = '';
  }

  echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "' method='get'>
        <label><span>Dato 1</span>
        <input type='text' name='numero1' placeholder='Introduce un número'/></label>";

  if ($params['errn1'] != '')
    echo "<p class='error'>{$params['errn1']}</p>";


  echo "<fieldset> <input type='submit' name='suma' value='+'>
  <input type='submit' name='resta' value='-'>
  <input type='submit' name='producto' value='*'>
  <input type='submit' name='division' value='/''>
  </fieldset>";

  echo "<label><span>Dato 2</span><input type='text' name='numero2' placeholder='Introduce un
  número'/></label> </form>";

  if ($params['errn2'] != '')
    echo "<p class='error'>{$params['errn2']}</p>";

  if ($params['errop'] != '')
    echo "<p class='error'>{$params['errop']}</p>";
}

?>