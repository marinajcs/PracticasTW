<?php

require('modelo/db.php');

/**
 * Valida las credenciales insertadas por el usuario, contemplando los posibles escenarios:
 * 1) Ya está identificado (sesión iniciada)
 * 2) Va iniciar sesión. En esta situación, dará la bienvenida si las ccredenciales son correctas,
 *    o dará un error en la autenticación en caso contrario.
 */
function userValidate()
{
    $db = DB_conexion();
    $accion['accion'] = '';
    $accion['email'] = '';
    $accion['tipoUser'] = '';

    // Comprobamos si el usuario está autenticado o no y decidimos qué hay que hacer
    if (isset($_SESSION['email']) && isset($_SESSION['rol'])) {
        $accion['accion'] = "yaidentificado";
        $email = $_SESSION['email'];
        $rol = $_SESSION['rol'];
        $accion['email'] = $email;
        $accion['tipoUser'] = $rol;

    } else if (isset($_POST['submit']) && isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST["email"];
        $passwd = $_POST["password"];

        $sql = "SELECT * FROM usuarios WHERE email = ? AND clave = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $passwd);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $accion['email'] = '$email';
            $accion['accion'] = "bienvenida";
            $_SESSION['email'] = $email;

            $query = "SELECT icono FROM usuarios WHERE email = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($res)) {
                $imagen = $row['icono'];
                $_SESSION['imagen'] = $imagen;
            }
            
            $sql = "SELECT * FROM administrador WHERE email = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $accion['tipoUser'] = "admin";
            } else {
                $accion['tipoUser'] = "colab";
            }
            $_SESSION['rol'] = $accion['tipoUser'];

            $mensaje = $_SESSION['email'] . " ha iniciado sesión";
            insertLog($mensaje, $db);
        } else {
            $accion['accion'] = "errorautenticacion";
        }

    } else
        $accion['accion'] = "formulario";

    mysqli_close($db);

    return $accion;
}

/**
 * Cierra la sesión del usuario, borrando todos los valores almacenados en
 * $_SESSION, para que lo redirija a la vista del visitante anónimo
 */
function userLogout()
{
    $db = DB_conexion();
    if (isset($_SESSION)){
        $mensaje = $_SESSION['email'] . " ha cerrado sesión";
        insertLog($mensaje, $db);
    }
    DB_desconexion($db);
    unset($_SESSION['email']);
    unset($_SESSION['rol']);
    unset($_SESSION['imagen']);
    unset($_SESSION['orden']);
    unset($_SESSION['buscar_texto']);
    unset($_SESSION['buscar_lugar']);
    unset($_SESSION['estados']);
    unset($_SESSION['nitems']);
}

?>