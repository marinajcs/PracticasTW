<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 3.2</title>
</head>

<body>

    <?php
    preg_match('#([^/]*php)/(.*)$#', $_SERVER['PHP_SELF'], $coincidencias);
    $chunks = (count($coincidencias) > 0) ? explode('/', $coincidencias[2]) : [];

    echo '<p>';
    echo "Operación: $coincidencias[2]";
    echo '</p>';

    $a = $_GET['a'];
    $b = $_GET['b'];

    if (isset($a) && is_numeric($a)) {
        echo '<p>';
        echo "Valor a: $a";
        echo '</p>';
    } else
        echo "Valor de a inválido";

    if (isset($b) && is_numeric($b)) {
        echo '<p>';
        echo "Valor b: $b";
        echo '</p>';
    } else
        echo "Valor de b inválido";

    if (count($chunks) > 0)
        echo '<pre>' . var_export($chunks, true) . '</pre>';
    else
        echo '<p>No hay trailing path</p>';

    echo '<p>';
    echo 'Resultado:';
    echo '</p>';

    if ($coincidencias[2] == 'multiplica')
        echo $_GET['a'] * $_GET['b'];
    else if ($coincidencias[2] == 'suma')
        echo $_GET['a'] + $_GET['b'];
    else if ($coincidencias[2] == 'resta')
        echo $_GET['a'] - $_GET['b'];
    else if ($coincidencias[2] == 'divide')
        echo $_GET['a'] / $_GET['b'];
    ?>


</body>

</html>