<?php

/**
 * Valida los datos recibidos del formulario.
 */
function validarDatos()
{
    $errores = array();
    $errores['nombre'] = '';
    $errores['email'] = '';
    $errores['password'] = '';
    $errores['rol'] = '';
    $errores['option'] = '';
    $errores['telefono'] = '';
    $errores['apellidos'] = '';
    $errores['direccion'] = '';
    $errores['foto'] = '';

    $datos = array();
    $datos['nombre'] = '';
    $datos['email'] = '';
    $datos['password'] = '';
    $datos['rol'] = '';
    $datos['option'] = '';
    $datos['telefono'] = '';
    $datos['apellidos'] = '';
    $datos['direccion'] = '';
    $datos['foto'] = '';

    //Comprobamos si el form se ha enviado
    if (isset($_POST) and !empty($_POST)) {

        //Comprobamos los datos recibidos 
        $datos = array(
            'nombre' => isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre'], ENT_QUOTES) : '',
            'email' => isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : '',
            'password' => isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : '',
            'rol' => isset($_POST['rol']) ? htmlspecialchars($_POST['rol'], ENT_QUOTES) : '',
            'option' => isset($_POST['option']) ? htmlspecialchars($_POST['option'], ENT_QUOTES) : '',
            'telefono' => isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono'], ENT_QUOTES) : '',
            'apellidos' => isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos'], ENT_QUOTES) : '',
            'direccion' => isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion'], ENT_QUOTES) : '',
            'foto' => ''
        );

        //Validamos los datos
        $valida = "[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]|_|-"; //con esto no permitira la inyeccion de codigo del siguiente apartado
        //puse esto ya que no quiero que nuestro form acepte caracteres que no sean letras, pero si solo quiero comprobar que este o no vacio, seria:

        if (empty($datos['nombre']) || preg_match("/$valida/", $datos['nombre'])) {
            $errores['nombre'] = "La contraseña no es válida";
        }

        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL))
            $errores['email'] = "El email no es correcto";

        if (empty($datos['password']) || strlen($datos['password']) < 6) {
            $errores['password'] = "La contraseña debe tener al menos 6 caracteres";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=*])[A-Za-z\d@#$%^&+=]{6,}$/",  $datos['password'])) {
            $errores['password'] = "La contraseña tiene que tener al menos una minúscula, una mayúscula, un número y un carácter especial";
        }

        if (!preg_match("/^[0-9]{9}$/",  $datos['telefono']))
            $errores['telefono'] = "El teléfono no es correcto";

        if (empty($datos['rol']))
            $errores['rol'] = "Debe marcar algún item";

        if (empty($datos['option']))
            $errores['option'] = "Debe marcar algún item";
    }
    return array('datos' => $datos, 'errores' => $errores);
}

/**
 * Devuelve el valor para el campo de formulario especificado y los errores asociados.
 */
function valor($n, $errores)
{
    if (isset($_POST[$n]) && $errores[$n] == '') {
        if ($n === 'foto') {
            return 'value="' . htmlentities($_FILES['foto']['name'], ENT_QUOTES) . '"';
        } else {
            return 'value="' . htmlentities($_POST[$n], ENT_QUOTES) . '"';
        }
    }
}

/**
 * Comprueba si el valor del campo de formulario coincide con el valor especificado y marca el campo como seleccionado si es verdadero.
 */
function marcar($n, $v)
{
    if (isset($_POST[$n]) and ($_POST[$n] == $v))
        echo ' checked';
}

/**
 * Verifica si se deben deshabilitar los campos de formulario según la presencia de errores.
 */
function deshabilitar($errores)
{
    if (
        $errores['nombre'] == '' && $errores['email'] == '' && $errores['password'] == '' && $errores['rol'] == '' &&
        $errores['option'] == '' && $errores['telefono'] == '' && $errores['apellidos'] == '' && $errores['direccion'] == '' && isset($_POST['enviar'])
    ) {
        return true;
    }
    return false;
}

/**
 * Muestra los errores asociados a un campo de formulario específico.
 */
function mostrarErrores($errores, $i)
{
    if (!empty($errores[$i]))
        return '<p class="error">' . $errores[$i] . '</p>';
    else
        return '';
}

/**
 * Verifica si el valor del campo de formulario coincide con el valor especificado y lo selecciona si es verdadero.
 */
function seleccionar($n, $v)
{
    if (isset($_POST[$n]) and ($_POST[$n] == $v))
        return ' selected';
    else
        return '';
}

/**
 * Genera el formulario de edición de usuario y realiza las siguientes acciones:
 * 1. Valida los datos recibidos del formulario.
 * 2. Muestra el formulario con los errores encontrados.
 * 3. Si no hay errores y se ha enviado el formulario:
 *    - Inserta un mensaje de registro en la base de datos (log).
 *    - Inserta el nuevo usuario en la base de datos.
 *    - Cierra la conexión a la base de datos.
 *    - Muestra un mensaje de éxito en la página.
 */
function editUserForm()
{
    $errores = array();
    $datos = array();
    $r = '';
    $res = array();

    $res = validarDatos();
    $errores = $res['errores'];
    $datos = $res['datos'];
    $r .= showFormUser($errores);

    if (
        $errores['nombre'] == '' && $errores['email'] == '' && $errores['password'] == '' && $errores['rol'] == '' &&
        $errores['option'] == '' && $errores['telefono'] == '' && $errores['apellidos'] == '' && $errores['direccion'] == '' && isset($_POST['enviar'])
    ) {
        $db = DB_conexion();
        $mensaje = $_SESSION['email'] . " ha AÑADIDO un USUARIO en la base de datos";
        insertLog($mensaje, $db);
        insertarUsuarioBD($datos, $db);
        DB_desconexion($db);
        $r .= "<h3>Se ha añadido correctamente en la base de datos</h3>";
    }
    return $r;
}

/**
 * Formulario de edición de usuario.
 */
function showFormUser($errores)
{
    $r = '<div class="correcto">';
    if (
        $errores['nombre'] = '' && $errores['email'] = '' && $errores['password'] = '' && $errores['rol'] = '' &&
        $errores['option'] = '' && $errores['telefono'] = '' && $errores['apellidos'] = '' && $errores['direccion'] = '' && isset($_POST['enviar'])
    ) {
        $r .= "<h2>Se han modificado los datos del usuario</h2>";
    }
    $r .= '</div>';
    $r .= '<div class="container">';
    $r .= '<div class="editUser">';
    $r .= '<h1>Edición de usuario/a</h1>';
    $r .= '<form method="POST" action="" enctype="multipart/form-data" novalidate';
    $r .= '>';
    $r .= '<label>Foto:<input type="file" name="foto"></label>';

    /*if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['foto']['tmp_name'];
        $name = $_FILES['foto']['name'];
        move_uploaded_file($tmp_name, "https://void.ugr.es/~mlorenzoaragon2223/proyecto/imagenes/" . $name);
        $r .= '<img src="https://void.ugr.es/~mlorenzoaragon2223/proyecto/imagenes/' . $name . '" alt="Foto seleccionada">';
    }*/

    $r .= '<div class="entrada">';
    $r .= '<label for="nombre">Nombre: </label>';
    $r .= '<input name="nombre" placeholder="Escriba su nombre"';
    $r .= valor('nombre', $errores);
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= isset($_POST['enviar']) ? mostrarErrores($errores, 'nombre') : '';
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="apellidos">Apellidos: </label>';
    $r .= '<input name="apellidos" placeholder="Escriba sus apellidos"';
    $r .= valor('apellidos', $errores);
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= isset($_POST['enviar']) ? mostrarErrores($errores, 'apellidos') : '';
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="email">Email: </label>';
    $r .= '<input name="email" type="email" placeholder="Escriba su email"';
    $r .= valor('email', $errores);
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= isset($_POST['enviar']) ? mostrarErrores($errores, 'email') : '';
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="password">Contraseña: </label>';
    $r .= '<input name="password" type="password" placeholder="Escriba su contraseña"';
    $r .= valor('password', $errores);
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= isset($_POST['enviar']) ? mostrarErrores($errores, 'password') : '';
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="direccion">Dirección: </label>';
    $r .= '<input name="direccion" placeholder="Escriba su dirección"';
    $r .= valor('direccion', $errores);
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= isset($_POST['enviar']) ? mostrarErrores($errores, 'direccion') : '';
    $r .= '</div>';

    $r .= '<div class="entrada">';
    $r .= '<label for="telefono">Teléfono: </label>';
    $r .= '<input name="telefono" placeholder="Escriba su teléfono"';
    $r .= valor('telefono', $errores);
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '/>';
    $r .= '<p class="error">';
    $r .= isset($_POST['enviar']) ? mostrarErrores($errores, 'telefono') : '';
    $r .= '</div>';

    $r .= '<div class="opciones">';
    $r .= '<label>Rol:';
    $r .= '<select name="rol"';
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '>';
    $r .= '<option value="colab"';
    $r .= seleccionar('rol', 'colab');
    $r .= '>Colaborador</option>';
    $r .= '<option value="admin"';
    $r .= seleccionar('rol', 'admin');
    $r .= '>Administrador</option>';
    $r .= '</select>';
    $r .= '</label>';
    $r .= '</div>';

    $r .= '<div class="opciones">';
    $r .= '<label>Estado:';
    $r .= '<select name="option"';
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '>';
    $r .= '<option value="activo"';
    $r .= seleccionar('option', 'activo');
    $r .= '>Activo</option>';
    $r .= '<option value="inactivo"';
    $r .= seleccionar('option', 'inactivo');
    $r .= '>Inactivo</option>';
    $r .= '</select>';
    $r .= '</label>';
    $r .= '</div>';

    $r .= '<input type="submit" name="enviar" value="Enviar datos"';
    $r .= deshabilitar($errores) ? ' disabled' : '';
    $r .= '>';
    $r .= '</form>';
    $r .= '</div></div>';

    return $r;
}

?>