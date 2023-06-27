<?php

/**
 * Valida los datos recibidos del formulario.
 */
function validarDatos1()
{
    $errores = array();
    $errores['nombre'] = '';
    $errores['descripcion'] = '';
    $errores['lugar'] = '';
    $errores['clave'] = '';

    $datos = array();
    $datos['nombre'] = '';
    $datos['descripcion'] = '';
    $datos['lugar'] = '';
    $datos['clave'] = '';

    $imgs = array();

    //Comprobamos si el form se ha enviado
    if (isset($_POST) and !empty($_POST)) {

        // Obtener y sanear los valores de los campos del formulario
        $datos['nombre'] = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre'], ENT_QUOTES) : '';
        $datos['descripcion'] = isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion'], ENT_QUOTES) : '';
        $datos['lugar'] = isset($_POST['lugar']) ? htmlspecialchars($_POST['lugar'], ENT_QUOTES) : '';
        $datos['clave'] = isset($_POST['clave']) ? htmlspecialchars($_POST['clave'], ENT_QUOTES) : '';

        //Validamos los datos
        $valida = "[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]|_|-"; //con esto no permitira la inyeccion de codigo del siguiente apartado
        //puse esto ya que no quiero que nuestro form acepte caracteres que no sean letras, pero si solo quiero comprobar que este o no vacio, seria:

        if (empty($datos['nombre']) || preg_match("/$valida/", $datos['nombre'])) {
            $errores['nombre'] = "El título no es válido";
        }

        if (empty($datos['lugar']) || preg_match("/$valida/", $datos['lugar'])) {
            $errores['lugar'] = "El lugar no es válido";
        }

        if (empty($datos['descripcion'])) {
            $errores['descripcion'] = "La descripción no puede estar vacía";
        }

        if (empty($datos['clave'])) {
            $errores['clave'] = "Las palabras clave no pueden estar vacías";
        }

        if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes'])) {
            $imgs = $_FILES['imagenes'];
        }
    }
    return array('datos' => $datos, 'imgs' => $imgs, 'errores' => $errores);
}

/**
 * Devuelve el valor para el campo de formulario especificado y los errores asociados.
 */
function valor1($n, $errores)
{
    if (isset($_POST[$n]) && $errores[$n] == '') {
        if ($n == 'foto') {
            return 'value="' . htmlentities($_FILES['foto']['name'], ENT_QUOTES) . '"';
        } else {
            return 'value="' . htmlentities($_POST[$n], ENT_QUOTES) . '"';
        }
    }
}

/**
 * Comprueba si el valor del campo de formulario coincide con el valor especificado y marca el campo como seleccionado si es verdadero.
 */
function marcar1($n, $v)
{
    if (isset($_POST[$n]) and ($_POST[$n] == $v))
        echo ' checked';
}

/**
 * Verifica si se deben deshabilitar los campos de formulario según la presencia de errores.
 */
function deshabilitar1($errores)
{
    if (
        $errores['nombre'] == '' && $errores['descripcion'] == '' && $errores['lugar'] == '' &&
        $errores['clave'] == '' && isset($_POST['enviar'])
    ) {
        return true;
    }
    return false;
}

/**
 * Muestra los errores asociados a un campo de formulario específico.
 */
function mostrarErrores1($errores, $i)
{
    if (!empty($errores[$i]))
        return '<p class="error">' . $errores[$i] . '</p>';
    else
        return '';
}

/**
 * Verifica si el valor del campo de formulario coincide con el valor especificado y lo selecciona si es verdadero.
 */
function seleccionar1($n, $v)
{
    if (isset($_POST[$n]) and ($_POST[$n] == $v))
        return ' selected';
    else
        return '';
}

/**
 * Genera el formulario de nueva incidencia y realiza las siguientes acciones:
 * 1. Valida los datos recibidos del formulario.
 * 2. Muestra el formulario con los errores encontrados.
 * 3. Si no hay errores y se ha enviado el formulario:
 *    - Inserta un mensaje de registro en la base de datos (log).
 *    - Inserta la nueva incidencia en la base de datos.
 *    - Cierra la conexión a la base de datos.
 *    - Muestra un mensaje de éxito en la página.
 */
function newIncidencia()
{
    $errores = array();
    $datos = array();
    $r = '';
    $res = array();
    $imgs = array();

    $res = validarDatos1();
    $errores = $res['errores'];
    $datos = $res['datos'];
    $imgs = $res['imgs'];
    $r .= showIncidenciaForm($errores);

    if ($errores['nombre'] == '' && $errores['descripcion'] == '' && $errores['lugar'] == '' &&
        $errores['clave'] == '' && isset($_POST['enviar'])) {
        $db = DB_conexion();
        $mensaje = $_SESSION['email'] . " ha INSERTADO una INCIDENCIA en la base de datos";
        insertLog($mensaje, $db);
        insertarIncidenciaBD($datos, $imgs, $db);
        DB_desconexion($db);
        $r .= "<h3>Se ha insertado correctamente en la base de datos</h3>";
    }
    return $r;
}

/**
 * Formulario de nueva incidencia.
 */
function showIncidenciaForm($errores)
{
    $r = '<div class="correcto">';
    if (
        $errores['nombre'] = '' && $errores['descripcion'] = '' && $errores['lugar'] = '' &&
        $errores['clave'] = '' && isset($_POST['enviar'])
    ) {
        $r .= "<h2>Se ha añadido una nueva incidencia</h2>";
    }
    $r .= '</div>';
    $r .= '<div class="container">';
    $r .= '<div class="nincidencia">';
    $r .= '<h1>Nueva incidencia</h1>';
    $r .= '<form method="POST" action="" enctype="multipart/form-data" novalidate';
    $r .= '>';

    $r .= '<div class="entrada">';
    $r .= '<label for="nombre">Título: </label>';
    $r .= '<input name="nombre" placeholder="Escriba un título"';
    $r .= valor1('nombre', $errores);
    $r .= deshabilitar1($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= mostrarErrores1($errores, 'nombre');
    $r .= '</div>';

    $r .= '<div class="descrip">';
    $r .= '<label for="descripcion">Descripción: </label>';
    $r .= '<textarea name="descripcion" placeholder="Escriba sobre la incidencia" class="descripcion-input"';
    $r .= valor1('descripcion', $errores);
    $r .= deshabilitar1($errores) ? ' disabled' : '';
    $r .= '>' . htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES) . '</textarea>';
    $r .= '<p class="error">';
    $r .= mostrarErrores1($errores, 'descripcion');
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="lugar">Lugar: </label>';
    $r .= '<input name="lugar" placeholder="Escriba el lugar"';
    $r .= valor1('lugar', $errores);
    $r .= deshabilitar1($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= mostrarErrores1($errores, 'lugar');
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="clave">Palabras clave: </label>';
    $r .= '<input name="clave" placeholder="Escriba las palabras clave"';
    $r .= valor1('clave', $errores);
    $r .= deshabilitar1($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= mostrarErrores1($errores, 'clave');
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="imagenes_inc">Imágenes: </label>';
    $r .= '<input type="file" name="imagenes[]" multiple';
    $r .= deshabilitar1($errores) ? ' disabled' : '';
    $r .= '><br></div>';

    $r .= '<input type="submit" name="enviar" value="Enviar datos"';
    $r .= deshabilitar1($errores) ? ' disabled' : '';
    $r .= '>';

    $r .= '</form>';

    $r .= '</div></div>';

    return $r;
}

?>