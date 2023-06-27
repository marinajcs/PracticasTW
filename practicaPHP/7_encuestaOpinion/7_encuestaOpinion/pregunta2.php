<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P2</title>
</head>

<body>
    <h1>Pregunta 2</h1>

    <?php
    if (!isset($_COOKIE['respuesta1'])){
        header('Location: pregunta1.php');
        //exit;
    }

    if (isset($_POST['respuesta2'])){
        setcookie("respuesta2", $_POST['respuesta2'], time()+1000);
        //header('Location: pregunta2.php');
    }
    ?>

     <form action="pregunta3.php" method='POST'>
        <label>Considera que la profundidad del temario en estos temas es:
            <input type='radio' name='respuesta2' value='def'>
            Deficiente
            <input type='radio' name='respuesta2' value='ade'>
            Adecuada
            <input type='radio' name='respuesta2' value='exc'>
            Excesiva
            <input type='radio' name='respuesta2' value='ns'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>