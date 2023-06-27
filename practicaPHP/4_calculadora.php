<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 4</title>
    <style>
        main {
            font-family: Arial;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form {
            border: 2px solid lightgray;
            padding: 5px;
            display: inline-flex;
            align-items: center;
            background-color: lightblue;
        }

        fieldset {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px;
            display: flex;
            flex-direction: column;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php

    $numero1 = '';
    $numero2 = '';
    $op = '';
    $result = '';
    $err['numero1'] = '';
    $err['numero2'] = '';
    $err['div0'] = '';

    $recibido = false;
    $enviado = isset($_GET['operacion']);
    function validarDatos($n1, $n2, $op)
    {
        $err['numero1'] = '';
        $err['numero2'] = '';
        $err['div0'] = '';

        if (!isset($n1) or empty($n1)) {
            $err['numero1'] = 'ERROR: El primer dato está vacío';
        } else if (!is_numeric($n1)) {
            $err['numero1'] = 'ERROR: El primer dato no es numérico';
        }

        if (!isset($n2) or empty($n2)) {
            $err['numero2'] = 'ERROR: El segundo dato está vacío';
        } else if (!is_numeric($n2)) {
            $err['numero2'] = 'ERROR: El segundo dato no es numérico';
        }

        if ($op == '/' and $n2 == 0) {
            $err['div0'] = 'ERROR: No puede dividirse entre 0';
        }

        return $err;
    }

    if ($enviado) {
        $numero1 = $_GET['numero1'];
        $numero2 = $_GET['numero2'];
        $op = $_GET['operacion'];

        $err = validarDatos($numero1, $numero2, $op);

        if ($err['numero1'] == '' and $err['numero2'] == '')
            $recibido = true;
    }

    function calculaOp($n1, $n2, $op)
    {
        $res = 0;
        if ($op == '+') {
            echo "<p>Operación: suma</p>";
            $res = $n1 + $n2;
        } else if ($op == '-') {
            echo "<p>Operación: resta</p>";
            $res = $n1 - $n2;
        } else if ($op == '*') {
            echo "<p>Operación: producto</p>";
            $res = $n1 * $n2;
        } else if ($op == '/') {
            echo "<p>Operación: división</p>";
            $res = $n1 / $n2;
        }
        return $res;
    }
    ?>

    <main>
        <h1>Calculadora</h1>
        <form action="" method="GET" novalidate>
            <label><span>Dato 1</span><input type="text" name="numero1" placeholder="Introduce un número" 
            <?php if ($err['numero1'] == '') echo " value='$numero1'"; ?>></label>
            <fieldset>
                <legend>Operación</legend>
                <input type="submit" name="operacion" value="+">
                <input type="submit" name="operacion" value="-">
                <input type="submit" name="operacion" value="*">
                <input type="submit" name="operacion" value="/">
            </fieldset>
            <label><span>Dato 2</span><input type="text" name="numero2" placeholder="Introduce un número" 
            <?php if ($err['numero2'] == '') echo " value='$numero2'"; ?>></label>
        </form>

        <?php
        if ($recibido) {
            $result = calculaOp($numero1, $numero2, $op);
            echo "<p>Resultado: $result</p>";
        } else {
            if ($err['numero1'] != '')
                echo "<p class='error'>{$err['numero1']}</p>";

            if ($err['numero2'] != '' and $err['div0'] == '')
                echo "<p class='error'>{$err['numero2']}</p>";

            if ($err['div0'] != '')
                echo "<p class='error'>{$err['div0']}</p>"; 
        }
        ?>
    </main>
</body>

</html>