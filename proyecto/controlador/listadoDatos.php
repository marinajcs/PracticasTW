<?php

require('modelo/funciones_db.php');

/**
 * Genera el listado de usuarios con paginación y filtros.
 */
function listadoUsers()
{
    $db = DB_conexion();
    $datos = checkRequestPaginado($_GET, 10);
    $accion = '';
    $r = '';

    $numusuarios = getNumUsuariosBD($db);
    $usuarios = get_UsuariosBD($db, $datos['primero'], $datos['numitems']);

    // Mostrar listado
    if ($usuarios != false) {
        $r .= listadoUsuarios($usuarios);
        $mensaje = $_SESSION['email'] . " ha MIRADO la lista de USUARIOS";
        insertLog($mensaje, $db);
    } else
        echo 'Ha habido un error en la consulta a la BBDD';

    // Barra de paginación
    if ($datos['numitems'] > 0) {
        $r .= htmlNavpaginado('paginador', build_pagLinks($numusuarios, $datos['numitems'], $datos['primero'], 'gestUser'), $accion);
    }

    DB_desconexion($db);

    return $r;
}

/**
 * Genera el listado de incidencias con paginación y filtros según la opción especificada.
 */
function listadoIncidencias($opcion)
{
    $db = DB_conexion();
    $filtros = checkRequestFilters($_POST);

    $r = filtrosIncidendias($filtros);

    $nitems = $filtros['nitems'];
    $datos = checkRequestPaginado($_GET, $nitems);
    $accion = '';

    $numincid = 0;
    $res = get_IncidenciasBD($db, $opcion, $datos['primero'], $datos['numitems'], $filtros);
    $incid = $res['tabla'];
    $numincid = $res['nincid'];
    
    $img = get_ImagesBD($db, $incid, $datos['primero'], $datos['numitems']);
    // Mostrar listado
    if ($incid != false) {
        $r .= listadoincidenciasHTML($incid, $img);
        if (isset($_SESSION['email'])) {
            $mensaje = $_SESSION['email'] . " ha MIRADO la lista de INCIDENCIAS";
            insertLog($mensaje, $db);
        }
    } else
        $r .= "</p>No se encontró ninguna incidencia</p>";

    // Barra de paginación
    if ($datos['numitems'] > 0) {
        $r .= htmlNavpaginado('paginador', build_pagLinks($numincid, $datos['numitems'], $datos['primero'], 'ver_incidencias'), $accion);
    }

    DB_desconexion($db);

    return $r;
}

/**
 * Genera el listado de logs con paginación.
 */
function listadoLogs()
{
    $db = DB_conexion();
    $datos = checkRequestPaginado($_GET, 10);
    $accion = '';
    $r = '';

    $numlogs = getNumLogsBD($db);
    $logs = get_LogsBD($db, $datos['primero'], $datos['numitems']);

    // Mostrar listado de logs
    if ($logs != false) {
        $r .= listadoLogsHTML($logs);
        $mensaje = $_SESSION['email'] . " ha MIRADO la lista de LOGS";
        insertLog($mensaje, $db);
    } else {
        echo 'Ha habido un error en la consulta a la BBDD';
    }

    // Barra de paginación
    if ($datos['numitems'] > 0) {
        $r .= htmlNavpaginado('paginador', build_pagLinks($numlogs, $datos['numitems'], $datos['primero'], 'log'), $accion);
    }

    DB_desconexion($db);

    return $r;
}


// ************* Funciones auxiliares
// Verificar argumentos de la petición web
/**
 * Verifica los argumentos de la petición web para la paginación de elementos.
 */
function checkRequestPaginado($get, $nitems)
{
    $datos = [];

    // ************* Argumentos GET de la página
    //  primero: Primer item a visualizar
    //  items : cuantos items incluir (<=0 para ver todos)
    if (!isset($get['items']))
        $datos['numitems'] = $nitems; // Valor por defecto
    else if ($get['items'] < 1 || !is_numeric($get['items']))
        $datos['numitems'] = 0; // Para mostrar todos los items
    else
        $datos['numitems'] = $nitems;

    if ($datos['numitems'] == 0)
        $datos['primero'] = 0; // Ver todos los items
    else {
        $datos['primero'] = isset($get['primero']) ? $get['primero'] : 0;
        if ($datos['primero'] < 0 || !is_numeric($datos['primero']))
            $datos['primero'] = 0;
    }
    return $datos;
}

/**
 * Verifica los argumentos de la petición web para los filtros.
 */
function checkRequestFilters($post)
{
    $datos = [];
    if (isset($post['enviarCriterios'])) {
        if (isset($post['orden'])) {
            $datos['orden'] = $post['orden'];
        }
        if (isset($post['buscar_texto'])) {
            $datos['buscar_texto'] = htmlentities($post['buscar_texto']);
        } else {
            $datos['buscar_texto'] = '';
        }

        if (isset($post['buscar_lugar'])) {
            $datos['buscar_lugar'] = htmlentities($post['buscar_lugar']);
        } else {
            $datos['buscar_lugar'] = '';
        }

        if (isset($post['estados'])) {
            $datos['estados'] = $post['estados'];
        } else {
            $datos['estados'] = array();
        }

        if (isset($post['nitems'])) {
            if ($post['nitems'] == "3_items") {
                $datos['nitems'] = 3;
            } else if ($post['nitems'] == "10_items") {
                $datos['nitems'] = 10;
            } else {
                $datos['nitems'] = 20;
            }
        }
        $_SESSION['orden'] = $datos['orden'];
        $_SESSION['buscar_texto'] = $datos['buscar_texto'];
        $_SESSION['buscar_lugar'] = $datos['buscar_lugar'];
        $_SESSION['estados'] = $datos['estados'];
        $_SESSION['nitems'] = $datos['nitems'];

    } else {
        $datos['orden'] = $_SESSION['orden'] ?? 'recientes';
        $datos['buscar_texto'] = $_SESSION['buscar_texto'] ?? '';
        $datos['buscar_lugar'] = $_SESSION['buscar_lugar'] ?? '';
        $datos['estados'] = $_SESSION['estados'] ?? [];
        $datos['nitems'] = $_SESSION['nitems'] ?? 10;
    }

    return $datos;
}

?>