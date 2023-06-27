<?php

/**
 * Genera el HTML necesario para mostrar el formulario de restore de la BD
 */
function View_restore()
{
	return <<<HTML
	<div class='frm_restore'>
	  <form action='restore.php' method='POST' enctype="multipart/form-data">
		<div class='frm_input'>
		  <label for='fichero'>Fichero:</label>
		  <input type='file' name='fichero' />
		</div>
		<div class='frm_submit'>
		  <input type='submit' name='submit' value='Subir' />
		</div>
	  </form>
	</div>
	HTML;
}

/**
 * Genera el HTML necesario para mostrar el formulario de delete all de la BD
 */
function view_MsgDeleteAll()
{

	echo <<<HTML
	<div class='frm_restore'>
	  <form action='deleteAll.php' method='POST' enctype="multipart/form-data">
		<div class='frm_input'>
		  <label for='fichero'>Fichero:</label>
		  <input type="" name='fichero' />
		</div>
		<div class='frm_submit'>
		  <input type='submit' name='submit' value='Subir' />
		</div>
	  </form>
	</div>
	HTML;
}

/**
 * Genera el HTML necesario para la gestión de backup, restore y delete all de la BD
 */
function HTML_gestBD()
{
	$r = <<<HTML
	<script>
		function mostrarOpcion(opcion) {
			// Ocultar todas las opciones
			var opciones = document.getElementsByClassName('opcion');
			for (var i = 0; i < opciones.length; i++) {
				opciones[i].style.display = 'none';
			}
			// Mostrar la opción seleccionada
			document.getElementById(opcion).style.display = 'block';
		}
	</script>
	<div class="container">
	<div class="gestBD">
		<h2>Gestión de la BBDD</h2>
		<p>Indique la acción a realizar</p>
		<div class="todos-botones">
			<div class="boton-container">
				<button class="texto-botones" onclick="mostrarOpcion('opcion1')">Copia de seguridad (backup)</button>
			</div>
			<div class="boton-container">
				<button class="texto-botones" onclick="mostrarOpcion('opcion2')">Restaurar BD</button>
			</div>
			<div class="boton-container">
				<button class="texto-botones" onclick="mostrarOpcion('opcion3')">Eliminar BD</button>
			</div>
		</div>
	</div>
	</div>
	<div id="opcion1" class="opcion" style="display: none;">
	HTML;
	$r .= checkBackup();
	$r .= <<<HTML
	</div>
	<div id="opcion2" class="opcion" style="display: none;">
	HTML;
	$r .= validarRestore();
	$r .= <<<HTML
	</div>
	<div id="opcion3" class="opcion" style="display: none;">
	HTML;
	$r .= deleteBaseDatos();
	$r .= '</div>';

	return $r;
}

?>