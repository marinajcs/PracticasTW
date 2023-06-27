<?php

/* Página del índice que se abre por defecto 
Se irá construyendo el html de la página, dependiendo del parámetro "p" del $_GET
*/

if (session_status() == PHP_SESSION_NONE)
    session_start();

require('login.php');
require('vista/html.php');
include('controlador/edicionUser.php');
include('controlador/backup.php');
include('controlador/restore.php');
include('controlador/listadoDatos.php');
include('vista/form_restore.php');
include('controlador/nuevaIncidencia.php');
include('controlador/deleteAll.php');

$cuerpo = '';
$opc = $_GET['p'] ?? 'noRegistrado'; // Inicialmente el usuario no está registrado
$rol = '';

switch ($opc) {
    case 'login': // Página que se abre cuando se trata de iniciar sesión
        $u = userValidate();
        if ($u['accion'] == 'bienvenida') {
            $cuerpo = HTMLBienvenida();
            $cuerpo .= HTMLmensaje('Bienvenido/a a la web. Se ha autenticado correctamente');
            $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        } else if ($u['accion'] == 'yaidentificado') {
            $cuerpo = HTMLBienvenida();
            $cuerpo .= HTMLmensaje('Ud. ya está identificado. Debe hacer logout para cambiar de identificación');
            $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        } else if ($u['accion'] == 'errorautenticacion') {
            $cuerpo = HTMLBienvenida();
            $cuerpo .= HTMLmensaje('Las credenciales no son válidas, inténtelo de nuevo');
            $cuerpo .= FORM_login('index.php?p=login', '', '', '');
        } else {
            $cuerpo = HTMLBienvenida();
            $cuerpo .= FORM_login('index.php?p=login', '', '', '');
        }
        break;
    case 'logout': // Página que se abre cuando se cierra sesión
        userLogout();
        $cuerpo = HTMLBienvenida();
        $cuerpo .= HTMLmensaje('Hasta luego');
        $cuerpo .= FORM_login('index.php?p=login', '', '', '');
        break;
    case 'nincidencia': // Página de gestión de nuevas incidencias
        $cuerpo .= newIncidencia();
        $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        break;
    case 'ver_incidencias': // Página que muestra todas las incidencias
        $cuerpo .= listadoIncidencias("ver_incidencias");
        $cuerpo .= FORM_login(
            'index.php?p=login', isset($_SESSION['email']) ? $_SESSION['email'] : '',
            isset($_SESSION['rol']) ? $_SESSION['rol'] : '', isset($_SESSION['imagen']) ? $_SESSION['imagen'] : ''
        );
        break;
    case 'mis_incidencias': // Página que muestra todas las incidencias del usuario logueado
        $cuerpo .= listadoIncidencias("mis_incidencias");
        $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        break;
    case 'log': // Página que muestra todos los logs
        $cuerpo .= listadoLogs();
        $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        break;
    case 'gestUser': // Página de gestión de usuarios (listado y añadir nuevos)
        $cuerpo .= gestUserHTML();
        $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        break;
    case 'gestBD': // Página de gestión de operaciones de backup, restore y delete all
        $cuerpo .= HTML_gestBD();
        $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        break;
    case 'copyright': // Muestra más detalles del elemento "copyright" del footer
        $cuerpo .= showCopyright();
        $cuerpo .= FORM_login(
            'index.php?p=login', isset($_SESSION['email']) ? $_SESSION['email'] : '',
            isset($_SESSION['rol']) ? $_SESSION['rol'] : '', isset($_SESSION['imagen']) ? $_SESSION['imagen'] : ''
        );
        break;
    case 'manual': // Muestra más detalles del elemento "manual" del footer
        $cuerpo .= showManualUso();
        $cuerpo .= FORM_login(
            'index.php?p=login', isset($_SESSION['email']) ? $_SESSION['email'] : '',
            isset($_SESSION['rol']) ? $_SESSION['rol'] : '', isset($_SESSION['imagen']) ? $_SESSION['imagen'] : ''
        );
        break;
    case 'nosotras': // Muestra más detalles del elemento "nosotras" del footer
        $cuerpo .= showQuienesSomos();
        $cuerpo .= FORM_login(
            'index.php?p=login', isset($_SESSION['email']) ? $_SESSION['email'] : '',
            isset($_SESSION['rol']) ? $_SESSION['rol'] : '', isset($_SESSION['imagen']) ? $_SESSION['imagen'] : ''
        );
        break;
    case 'noRegistrado': // Página que no necesita autenticación. La que se abre por defecto
        $cuerpo = HTMLBienvenida();
        $cuerpo .= HTMLmensaje('Bienvenido/a Anónimo/a');
        $cuerpo .= FORM_login('index.php?p=login', '', '', '');
        break;
    case 'edicionUser': // Página que gestiona las operaciones en la BD con usuarios
        $cuerpo .= editUserForm();
        $cuerpo .= FORM_login('', $_SESSION['email'], $_SESSION['rol'], $_SESSION['imagen']);
        break;

}

// Construir y mostrar página web
echo HTMLCrearPagina("Título", $cuerpo, isset($_SESSION['email']) ? $_SESSION['email'] : '', isset($_SESSION['rol']) ? $_SESSION['rol'] : '');

?>