<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P1</title>
</head>

<body>
    <h1>Pregunta 1</h1>

    <?php
    if (isset($_COOKIE['respuesta1']))
        setcookie("respuesta1", "", time() - 1000);
    if (isset($_COOKIE['respuesta2']))
        setcookie("respuesta2", "", time() - 1000);
    if (isset($_COOKIE['respuesta3']))
        setcookie("respuesta3", "", time() - 1000);
    if (isset($_COOKIE['respuesta4']))
        setcookie("respuesta4", "", time() - 1000);
    if (isset($_COOKIE['respuesta5']))
        setcookie("respuesta5", "", time() - 1000);

    if (isset($_POST['respuesta1'])){
        setcookie("respuesta1", $_POST['respuesta1'], time()+1000);
        header('Location: pregunta2.php');
        exit;
    }

    ?>

     <form action="pregunta2.php" method='POST'>
        <label>¿Tenía conocimientos previos de la materia explicada?
            <input type='radio' name='respuesta1' value='si'>
            Sí
            <input type='radio' name='respuesta1' value='no'>
            No
            <input type='radio' name='respuesta1' value='ns'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>