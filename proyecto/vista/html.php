<?php

/**
 * Construir una página web completa
 *  $titulo -> contenido del TAG <title>
 *  $cuerpo -> contenido HTML principal de la página
 *  $usuario -> Si vale '' entonces el usuario no está identificado
 */

function HTMLCrearPagina($titulo, $cuerpo, $usuario, $rol)
{
  $r = <<<HTML
  <!DOCTYPE html>
  <html lang="es">
    <head>
    <meta charset="UTF-8">
    <title>Voces sin silencio</title>
    <link rel="stylesheet" type="text/css" href="indice.css">
    </head>
    <body>
  HTML;
  $r .= HTMLencabezado();
  $r .= <<<HTML
    <main>
  HTML;

  if ($rol == 'admin')
    $r .= HTMLmenuAdmin();
  else if ($rol == 'colab')
    $r .= HTMLmenuColab();
  else
    $r .= HTMLmenuAnon();

  $r .= $cuerpo;

  $r .= <<<HTML
    </main>
  HTML;
  $r .= HTMLpiepagina();
  $r .= <<<HTML
    </body>
  </html>
  HTML;
  return $r;
}

/**
 * Genera el HTML necesario para la barra de navegación del visitante anónimo
 */
function HTMLmenuAnon()
{
  return <<<HTML
    <div class="container">
    <nav>
    <ul>
      <li><a href="index.php?p=ver_incidencias">Ver incidencias</a></li>
    </ul>
    </nav>
    </div>
  HTML;
}

/**
 * Genera el HTML necesario para la barra de navegación del colaborador
 */
function HTMLmenuColab()
{
  return <<<HTML
    <div class="container">
    <nav>
    <ul>
      <li><a href="index.php?p=ver_incidencias">Ver incidencias</a></li>
      <li><a href="index.php?p=nincidencia">Nueva incidencia</a></li>
      <li><a href="index.php?p=mis_incidencias">Mis incidencias</a></li>
    </ul>
    </nav>
    </div>
  HTML;
}

/**
 * Genera el HTML necesario para la barra de navegación del administrador
 */
function HTMLmenuAdmin()
{
  return <<<HTML
    <div class="container">
    <nav>
    <ul>
      <li><a href="index.php?p=ver_incidencias">Ver incidencias</a></li>
      <li><a href="index.php?p=nincidencia">Nueva incidencia</a></li>
      <li><a href="index.php?p=mis_incidencias">Mis incidencias</a></li>
      <li><a href="index.php?p=gestUser">Gestión de usuarios</a></li>
      <li><a href="index.php?p=log">Ver log</a></li>
      <li><a href="index.php?p=gestBD">Gestión de BBDD</a></li>
    </ul>
    </nav>
    </div>
  HTML;
}

/**
 * Genera el HTML necesario para mostrar el mensaje de bienvenida 
 */
function HTMLBienvenida()
{
  return <<<HTML
    <div class="container">
      <section>
        <h2 class="welcome">Bienvenido/a a este sitio</h2>
        <p>Aquí podrás consultar las quejas de los vecinos de Albolote. Además, si se da de alta en la web podrá incluir
          sus propias quejas para que sean tenidas en cuenta.</p>
        <p>Para darse de alta envíe un correo a cualquiera de los administradores del sitio, por ejemplo a
          admin@gmail.com y estos contactarán con usted en cuanto les sea posible.</p>
      </section>
    </div>
    HTML;
}

/**
 * Genera el HTML necesario para el pie de página o footer
 */
function HTMLpiepagina()
{
  return <<<HTML
  <footer>
    <p class="copyright"><a href="index.php?p=copyright">(C)Tecnologías Web</a></p>
    <p class="manualUso"><a href="index.php?p=manual">Manual de uso</a></p>
    <p class="acercaDe"><a href="index.php?p=nosotras">Marina Jun Carranza Sánchez y Mercedes Lorenzo Aragón</a></p>
  </footer>
  HTML;
}

/**
 * Genera el HTML necesario para el formulario de login, con el ranking incluido
 */
function FORM_login($action, $user, $rol, $img)
{
  if ($user == '') {
    $r = <<<HTML
    <div class="container">
    <aside class="info_perfil">
      <form method="POST" action="$action">
          <label for="email">Email: </label>
          <input name="email" placeholder="Escribe tu email" type="text" id="email" required>
          <label for="password">Clave: </label>
          <input name="password" placeholder="Escribe tu clave" type="password" id="password" required>
        <input type="submit" name="submit" value="Login">
      </form>
    HTML;
  } else {
    $r = <<<HTML
    <div class="container">
    <aside class="info_perfil">
      <h2 class="login_title">Bienvenido/a</h2>
      <div id="imagen-container">
    HTML;
    $r .= '<img src="data:image/jpeg;base64,' . base64_encode($img) . '">';
    $r .= <<<HTML
      </div>
      <p>$user</p>
      <h3>$rol</h3>
      <a href="index.php?p=editUser">Editar</a>
      <a href="index.php?p=logout">Logout</a>
    HTML;
  }
  $db = DB_conexion();
  $ranking = get_RankingPersonas($db);
  $r .= getRankingHTML($ranking);
  $r .= '</aside></div>';
  DB_desconexion($db);

  return $r;

}

/**
 * Genera el HTML necesario para el encabezado del sitio web
 */
function HTMLencabezado()
{
  $r = <<<HTML
  <body>
  <header>
    <img src="logo.png">
    <h1>Voces sin silencio</h1>
  </header>
  HTML;
  return $r;
}

/**
 * Genera el HTML necesario para mostrar un mensaje cualquiera e integrarlo en la página
 */
function HTMLmensaje($msg)
{
  return <<<HTML
  <div class="bienvenida">
  <h3>$msg</h3>
  </div>
  HTML;
}

/**
 * Genera el HTML necesario para el elemento "manual" del footer
 */
function showManualUso()
{
  $r = <<<HTML
  <div class="manual">
    <h2>Uso de la aplicación</h2>
    <p>Instrucciones de uso:</p>
    <p>Podemos encontrar toda la funcionalidad, datos, diagramas, wireframes, etc, del proyecto en este PDF:</p>
    <a href="ProyectoFinalTW.pdf" target="_blank">Memoria</a>
  </div>
  HTML;
  return $r;
}

/**
 * Genera el HTML necesario para el elemento "copyright" del footer
 */
function showCopyright()
{
  $r = <<<HTML
  <div class="copyr">
    <h2>Copyright</h2>
    <p>Marina Jun Carranza Sánchez y Mercedes Lorenzo Aragón</p>
    <p>Tecnologías Web. Grado en Ingeniería Informática</p>
    <p>Universidad de Granada</p>
    <p></p>
    <h2>Condiciones de uso</h2>
    <p>Proyecto final para la asignatura Tecnologías Web. No se permite su uso en otro contexto.</p>
    <p>Algunas imágenes son cogidas de Google y los iconos de los usuarios son de flaticon.</p>
  </div>
  HTML;
  return $r;
}

/**
 * Genera el HTML necesario para el elemento "nosotras" del footer
 */
function showQuienesSomos()
{
  $r = <<<HTML
  <div class="nosotras">
    <h2>¿Quiénes somos?</h2>
    <p>Marina Jun Carranza Sánchez</p>
    <p>marinacarranza@correo.ugr.es</p>
    <p>Mercedes Lorenzo Aragón</p>
    <p>mlorenzoaragon@correo.ugr.es</p>
  </div>
  HTML;
  return $r;
}

/**
 * Genera el HTML necesario para la navegación del paginado
 */
function htmlNavpaginado($clase, $menu, $accion, $activo = '')
{
  $r = "<nav id='nav2'>";
  foreach ($menu as $elem) {
    $r .= "<a class='negro' " . ($activo == $elem['texto'] ? "class='activo' " : '') . "href='{$elem['url']}'>{$elem['texto']}</a>";
    if (end($menu) != $elem)
      $r .= "|";
  }

  $r .= '</nav>';

  return $r;
}

/**
 * Genera el HTML necesario para moverse entre las páginas de un listado cualquiera
 */
function build_pagLinks($numusuarios, $numitems, $primero, $pagina)
{
  $links = [];
  $ultima = $numusuarios - ($numusuarios % $numitems);
  $anterior = $numitems > $primero ? 0 : ($primero - $numitems);
  $siguiente = ($primero + $numitems) > $numusuarios ? $ultima : ($primero + $numitems);

  $urlParams = "&primero=$primero&items=$numitems";
  $filters = '';

  $links[] = ['texto' => 'Primera', 'url' => "?p=$pagina&primero=0&items=" . urlencode($numitems)];
  $links[] = ['texto' => 'Anterior', 'url' => "?p=$pagina&primero=" . urlencode($anterior) . "&items=". urlencode($numitems)];
  $links[] = ['texto' => 'Siguiente', 'url' => "?p=$pagina&primero=" . urlencode($siguiente) . "&items=" . urlencode($numitems)];
  $links[] = ['texto' => 'Última', 'url' => "?p=$pagina&primero=" . urlencode($ultima) . "&items=" . urlencode($numitems)];

  return $links;
}

/**
 * Genera el HTML necesario para el listado de usuarios
 */
function listadoUsuarios($datos)
{
  $r = <<<HTML
  <div class='listado'><table><tr style="background-color: rgb(229, 198, 94);">
  <th>Icono</th><th>Nombre</th> <th>Apellidos</th> <th>Email</th><th>Teléfono</th><th>Dirección</th> <th>Rol</th><th>Estado</th> <th>Acción</th></tr>
  HTML;
  $db = DB_conexion();
  $rol = '';
  $fila = 0;

  foreach ($datos as $v) {
    $email = $v['email'];
    $sql = "SELECT * FROM administrador WHERE email = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
      $rol = "admin";
    } else {
      $rol = "colab";
    }

    // Aplica diferentes colores a las filas pares e impares
    $color = ($fila % 2 == 0) ? 'rgb(229, 198, 94)' : 'rgb(235, 217, 160)';

    $r .= "<tr style='background-color: $color;'> <td>" . '<div id="imagen-container">' .
      '<img src="data:image/jpeg;base64,' . base64_encode($v['icono']) . '">' . '</div> </td>';
    $r .= "   <td><span style='color: blue;'>{$v['nombre']}</span></td>";
    $r .= "   <td><span style='color: blue;'>{$v['apellidos']}</span></td>";
    $r .= "   <td><span style='color: blue;'>{$v['email']}</span></td>";
    $r .= "   <td><span style='color: blue;'>{$v['telefono']}</span></td>";
    $r .= "   <td><span style='color: blue;'>{$v['direccion']}</span></td>";

    if ($rol == 'admin')
      $r .= "     <td class='rol_user' style='color: blue;'>Administrador</td>";
    else
      $r .= "     <td class='rol_user' style='color: blue;'>Colaborador</td>";

    if ($v['estado'] == 'A')
      $r .= "     <td class='estado_user' style='color: blue;'>Activo</td>";
    else
      $r .= "     <td class='estado_user' style='color: blue;'>Inactivo</td>";

    if ($v['estado'] == 'A') {
      $r .= "<td> <form action='index.php?p=edicionUser' method='POST'>
              <input type='hidden' name='Email' value='{$v['email']}' />
              <input type='submit' name='accion' value='Editar' />";

      if ($v['email'] != $_SESSION['email'])
        $r .= "<form action='index.php?p=edicionUser' method='POST'>
      <input type='hidden' name='Email' value='{$v['email']}' />
      <input type='submit' name='accion' value='Borrar' />";

      $r .= "</form>";

    } else if ($v['estado'] == 'I') {
      $r .= "<td> <form action='Controlador/procesar.php' method='POST'>";
      $r .= "<input type='hidden' name='Email' value='{$v['email']}' />";
      $r .= "<input type='submit' name='accion' value='Activar'/>";
      $r .= "</form>";
    }

    $r .= "</td>";
    $r .= "</tr>";

    $fila++;
  }
  DB_desconexion($db);

  $r .= "</table></div>";

  return $r;
}

/**
 * Genera el HTML necesario para el listado de incidencias
 */
function listadoincidenciasHTML($datos, $imgs)
{
  
  $r = <<<HTML
  <div class='listado_incidencias'>
  HTML;
  foreach ($datos as $v) {
    $r .= "<div id='contenedor-incidencia' style='padding: 10px; background-color: rgb(245, 234, 198);'>
         <h4> <span style='color: white; background-color: blue; padding: 5px;'>{$v['titulo']}</h4> ";
    $r .= "<p>Lugar: <span style='color: blue;'>{$v['lugar']}</span> | Fecha: <span style='color: blue;'>{$v['fecha_hora']}</span> | Creado por: <span style='color: blue;'>{$v['email']}</span> | Estado: <span style='color: blue;'>{$v['estado']}</span> | Palabras clave: <span style='color: blue;'>{$v['palabras_clave']}</span> | Valoraciones: <span style='color: blue;'>Pos: {$v['likes']}  Neg: {$v['dislikes']}</span> </p>";
    $r .= "<p>{$v['descripcion']} </p>";

    if (isset($imgs[$v['ID_Incidencia']])) {
      foreach ($imgs[$v['ID_Incidencia']] as $i) {
        if ($i != '') {
          $r .= "<img src='data:image/jpeg;base64," . base64_encode($i) . "' style='max-width: 400px; max-height: 400px; margin: 3px;'>";
        }
      }
    }
    $r .= "</div>";
  }

  $r .= "</div>";
  return $r;
}

/**
 * Genera el HTML necesario para el ranking de usuarios con más incidencias creadas
 */
function getRankingHTML($datos)
{
  $r = <<<HTML
    <div id='contenedor-ranking' style='padding: 10px; background-color: rgb(245, 234, 198);'>
    <p> <span style='color: white; background-color: blue; padding: 5px;'>Los que más añaden</p>
  HTML;
  $n = 0;
  foreach ($datos as $v) {
    $n += 1;
    $r .= "<p><span style='color: black;'>$n. ({$v['total_incidencias']}) {$v['email']}</span></p>";
  }
  $r .= "</div>";
  return $r;
}

/**
 * Genera el HTML necesario para el mensaje de borrado de tablas
 */
function borradoTablasCorrecto()
{
  echo "<div class='en_linea'><main>
  <p>El borrado de todas las tablas de la base de datos (excepto el usuario logueado que ha ejecutado la orden) ha sido realizado con éxito</p>
  </main>";
}

/**
 * Genera el HTML necesario para los filtros de ver incidencias/mis incidencias
 */
function filtrosIncidendias($filtros)
{
  $r = <<<HTML
  <div id="filtrosIncidencias" style="background-color: rgb(229, 198, 94); margin: 5px; padding-top: 3px;">
    <form action="" method="POST">
      <fieldset style="background-color: rgb(235, 217, 160); margin: 10px;">
        <legend>Ordenar por:</legend>
  HTML;
  $r .= '<input type="radio" id="opcion1RB" name="orden" value="recientes"';
  if ($filtros['orden'] == 'recientes')
    $r .= ' checked';
  $r .= '>
        <label for="opcion1RB">Las más recientes</label>
        <input type="radio" id="opcion2RB" name="orden" value="likes_desc"';
  if ($filtros['orden'] == 'likes_desc')
    $r .= ' checked';
  $r .= '>
        <label for="opcion2RB">Número de likes</label>
        <input type="radio" id="opcion3RB" name="orden" value="dislikes_desc"';
  if ($filtros['orden'] == 'dislikes_desc')
    $r .= ' checked';
  $r .= '>
        <label for="opcion3RB">Número de dislikes</label>
      </fieldset>
      <fieldset style="background-color: rgb(235, 217, 160); margin: 10px;">
        <legend>Incidencias que contengan:</legend>
        <label for="texto1">Texto de búsqueda:</label>
        <input type="text" id="texto1" name="buscar_texto" value=' . $filtros['buscar_texto'] . '>
        <label for="texto2">Lugar:</label>
        <input type="text" id="texto2" name="buscar_lugar" value=' . $filtros['buscar_lugar'] . '>
      </fieldset>
      <fieldset style="background-color: rgb(235, 217, 160); margin: 10px;">
        <legend>Estado:</legend>
        <input type="checkbox" id="checkbox1" name="estados[]" value="pendiente"';
  if (in_array('pendiente', $filtros['estados']))
    $r .= ' checked';
  $r .= '>
        <label for="checkbox1">Pendiente</label>
        <input type="checkbox" id="checkbox2" name="estados[]" value="comprobada"';
  if (in_array('comprobada', $filtros['estados']))
    $r .= ' checked';
  $r .= '>
        <label for="checkbox2">Comprobada</label>
        <input type="checkbox" id="checkbox3" name="estados[]" value="tramitada"';
  if (in_array('tramitada', $filtros['estados']))
    $r .= ' checked';
  $r .= '>
        <label for="checkbox3">Tramitada</label>
        <input type="checkbox" id="checkbox4" name="estados[]" value="irresoluble"';
  if (in_array('irresoluble', $filtros['estados']))
    $r .= ' checked';
  $r .= '>
        <label for="checkbox4">Irresoluble</label>
        <input type="checkbox" id="checkbox5" name="estados[]" value="resuelta"';
  if (in_array('resuelta', $filtros['estados']))
    $r .= ' checked';
  $r .= '>
        <label for="checkbox5">Resuelta</label>
      </fieldset>
  
      <label for="select" style="margin: 10px;">Incidencias por página:</label>
      <select id="select" name="nitems">
      <option value="3_items"';
  if ($filtros['nitems'] == '3_items')
    $r .= ' selected';
  $r .= '>3 items</option>
      <option value="10_items"';
  if ($filtros['nitems'] == '10_items')
    $r .= ' selected';
  $r .= '>10 items</option>
      <option value="20_items"';
  if ($filtros['nitems'] == '20_items')
    $r .= ' selected';
  $r .= '>20 items</option>
      <input type="submit" name="enviarCriterios" value="Aplicar criterios de búsqueda" style="margin: 10px;">
    </form>
  </div>';

  return $r;
}

/**
 * Genera el HTML necesario para la página de gestión de usuarios
 */
function gestUserHTML()
{
  $r = <<<HTML
	<script>
		function mostrarOpcionUser(opcion) {
			var opciones = document.getElementsByClassName('opcion');
			for (var i = 0; i < opciones.length; i++) {
				opciones[i].style.display = 'none';
			}
			document.getElementById(opcion).style.display = 'block';
		}
	</script>
	<div class="container">
	<div class="gestBD">
		<h2>Gestión de usuarios</h2>
		<p>Indique la acción a realizar</p>
		<div class="todos-botones">
			<div class="boton-container">
				<button class="texto-botones" onclick="mostrarOpcionUser('opcion1')">Listado de usuarios</button>
			</div>
			<div class="boton-container">
				<button class="texto-botones" onclick="mostrarOpcionUser('opcion2')">Añadir nuevo</button>
			</div>
		</div>
	</div>
	</div>
	<div id="opcion1" class="opcion" style="display: block;">
	HTML;
  $r .= listadoUsers();
  $r .= <<<HTML
	</div>
	<div id="opcion2" class="opcion" style="display: none;">
	HTML;
  $r .= editUserForm();
  $r .= '</div>';

  return $r;
}

?>