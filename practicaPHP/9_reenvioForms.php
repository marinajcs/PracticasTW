<?php
session_start();
if (isset($_POST['borrar'])){
    unset($_SESSION['total']);
}

if (!isset($_SESSION['total']) or !isset($_SESSION['numero'])) {
    $_SESSION['numero'] = 0;
    $_SESSION['total'] = 0;
}

if (isset($_POST['enviar']) and is_numeric($_POST['donacion'])) {
    $_SESSION['numero']++;
    $_SESSION['total'] += $_POST['donacion'];
    header( "Location: {$_SERVER['SCRIPT_NAME']}", true, 303);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Ejercicio 9</title>
</head>

<body>
    <?php
    echo "<p>Hasta ahora hay un total de {$_SESSION['numero']} donaciones.</p>";
    echo "<p>El importe acumulado es de {$_SESSION['total']} euros.</p>";
    if (isset($_SESSION['donacion']) and is_numeric($_SESSION['donacion'])){
        echo "<p>La última donación fue de {$_SESSION['donacion']} euros</p>";
    }

    echo form();
    ?>
</body>
</html>
<?php
function form(){
    return <<<HTML
        <form action="{$_SERVER['SCRIPT_NAME']}" method="post">
            <label>Realizar una nueva donación: <input type="text" name="donacion" size="10"></label>
            <p><input type="submit" name="enviar" value="Enviar">
                <input type="submit" name="borrar" value="Borrar sesión"></p>
        </form>\n
        HTML;
} 
?>