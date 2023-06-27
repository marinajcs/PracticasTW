<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 8</title>
    <style>
        label {
            display: block;
        }
    </style>
</head>

<body>
    <h1>Generador de números aleatorios</h1>

    <?php
    session_start();

    if (isset($_POST['nombre']) and $_POST['nombre'] != ''){
        session_destroy();
        session_start();
        $_SESSION['nombre'] = $_POST['nombre'];
    }

    if (isset($_POST['borrar'])){
        session_destroy();
    }

    if (!isset($_SESSION['nombre']) or isset($_POST['borrar'])) {
        echo "<form action='".$_SERVER["SCRIPT_NAME"]."' method='POST'>
                <label>Dígame su nombre para comenzar:
                    <input type='text' name='nombre' required>
                    <input type='submit' name='aceptar' value='Aceptar'>
                    <input type='submit' name='borrar' value='Borrar sesión'>
                </label>
            </form>";
    } else {
        if (!isset($_SESSION['nums']))
            $_SESSION['nums'] = [];

        echo "<p>¡Bienvenido/a, <strong>";
        echo $_SESSION['nombre'];
        echo "</strong>!</p>";

        echo "<ol>";
        foreach ($_SESSION['nums'] as $n) {
            echo "<li>$n</li>";
        }
        echo "</ol>";

        $n = rand(1,9999999999);
        array_push($_SESSION['nums'], $n);

        echo "<p>El nuevo número es: $n</p>";

        echo "<form action='".$_SERVER["SCRIPT_NAME"]."' method='POST'>
                <label>Dígame su nombre para comenzar:
                    <input type='text' name='nombre'>
                    <input type='submit' name='aceptar' value='Aceptar'>
                    <input type='submit' name='borrar' value='Borrar sesión'>
                </label>
            </form>";
    }

    echo "<p><a href='" . $_SERVER["SCRIPT_NAME"] . "'>Cargar de nuevo</a></p>";

    ?>

    

    


</body>

</html>