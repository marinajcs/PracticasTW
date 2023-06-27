<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P3</title>
</head>

<body>
    <h1>Pregunta 3</h1>

    <?php
    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2'])){
        header('Location: pregunta1.php');
        //exit;
    }

    if (isset($_POST['respuesta3'])){
        setcookie("respuesta3", $_POST['respuesta3'], time()+1000);
        //header('Location: pregunta2.php');
    }
    ?>

     <form action="pregunta4.php" method='POST'>
        <label>Las explicaciones de teoría son:
            <input type='radio' name='respuesta3' value='mal'>
            Malas
            <input type='radio' name='respuesta3' value='nor'>
            Normales
            <input type='radio' name='respuesta3' value='bue'>
            Buenas
            <input type='radio' name='respuesta3' value='ns'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>