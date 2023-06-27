<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>3 en raya</title>
    <style>
        body {
            font-family: helvetica;
        }

        .juego {
            width: 200px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .juego>h1 {
            width: 100%;
            background-color: lightgreen;
            text-align: center;
        }

        .juego>.informacion {
            width: 100%;
            background-color: lightgreen;
        }

        .informacion {
            margin: 5px 0;
            padding: 5px;
        }

        .informacion img {
            vertical-align: middle;
        }

        .informacion p {
            text-align: center;
            margin: auto;
        }

        .libre {
            transition: transform .5s ease-in-out;
        }

        .libre:hover {
            transform: scale(1.5);
            cursor: pointer;
        }

        .ganador {
            animation: anim 0.5s infinite linear alternate;
        }

        @keyframes anim {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.5);
            }
        }
    </style>
</head>

<?php

$jugador = 'brojo.png';
$fil_act = '';
$col_act = '';
$tablero = array();

function inicializarTablero()
{
    global $tablero;

    $tablero = array();

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $tablero[$i][$j] = 'bamarillo.png';
        }
    }
    return $tablero;
}

// Función para obtener el código HTML del tablero
function generarHTMLTablero($tablero)
{
    $htmlTablero = "<table>";
    for ($i = 0; $i < 3; $i++) {
        $htmlTablero .= "<tr>";
        for ($j = 0; $j < 3; $j++) {
            $htmlTablero .= '<td><input type="image" class="libre" src="' . $tablero[$i][$j] . '" width="50px" formmethod="post" name="poner' . $i . $j . '" /></td>';
        }
        $htmlTablero .= '</tr>';
    }
    $htmlTablero .= '</table>';
    return $htmlTablero;
}

// Inicializar el tablero si es la primera vez
if (!isset($tablero) or empty($tablero)) {
    $tablero = inicializarTablero();
    $jugador = 'brojo.png';
}

// Obtener las coordenadas de la casilla seleccionada
if (isset($_POST['poner00'])) {
    $fil_act = 0;
    $col_act = 0;
} elseif (isset($_POST['poner01'])) {
    $fil_act = 0;
    $col_act = 1;
} elseif (isset($_POST['poner02'])) {
    $fil_act = 0;
    $col_act = 2;
} elseif (isset($_POST['poner10'])) {
    $fil_act = 1;
    $col_act = 0;
} elseif (isset($_POST['poner11'])) {
    $fil_act = 1;
    $col_act = 1;
} elseif (isset($_POST['poner12'])) {
    $fil_act = 1;
    $col_act = 2;
} elseif (isset($_POST['poner20'])) {
    $fil_act = 2;
    $col_act = 0;
} elseif (isset($_POST['poner21'])) {
    $fil_act = 2;
    $col_act = 1;
} elseif (isset($_POST['poner22'])) {
    $fil_act = 2;
    $col_act = 2;
}

$htmlTablero = generarHTMLTablero($tablero);


if ($fil_act != '' and $col_act != '') {
    if ($tablero[$fil_act][$col_act] == 'bamarillo.png') {
        $tablero[$fil_act][$col_act] = $jugador;

        if ($jugador == 'brojo.png')
            $jugador = 'bazul.png';
        else
            $jugador = 'brojo.png';
    }
}

if (isset($_POST['limpiar'])) {
    $tablero = inicializarTablero();
    $jugador = "brojo.png";
}


?>

<html>

<body>
    <section class="juego">
        <h1>3 en raya</h1>
        <section class="tablero">
            <form method="post" action="">
                <?php echo $htmlTablero; ?>
            </form>
        </section>
        <section class="informacion">
            <?php echo "<p>Turno: <img src='" . $jugador . "' width='25px'/></p>"; ?>
        </section>
        <section class="botones">
            <form method="post" action="">
                <input type="submit" name="limpiar" value="Limpiar" />
        </section>
    </section>

</body>

</html>