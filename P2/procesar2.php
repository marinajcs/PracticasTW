<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Variables recibidas</title>
    <style type="text/css">
      .valor { color: red; }
    </style>
  </head>
<body>
  <?php echo "<h1>Variables recibidas</h1>";
      showVar($_GET,'Desde $_GET');
      showVar($_POST,'Desde $_POST');
      showVarFiles();
      function showVar($var,$msg) {
        echo "<h2>$msg</h2>";
        echo "<ul>";
        foreach ($var as $c => $v) {
          if (is_array($v)) {
            echo "<li>$c = <span class='valor'>"; 
            print_r($v); 
            echo "</span></li>";
          } else
            echo "<li>$c = <span class='valor'>$v</span></li>";
        }
        echo "</ul>";        
      }
      function showVarFiles() {
        echo "<h2>Ficheros recibidos:</h2>";
        echo "<ul>";
        foreach ($_FILES as $c => $v) {
          echo "<li>$c = <pre class='valor'>";
          print_r($v);
          echo "</pre>";
          if (is_uploaded_file($v['tmp_name']))
            if (is_image($v['tmp_name']))
              echo '<img src="data:image/'.pathinfo($v['name'],PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($v['tmp_name'])).'">';
            else
              echo '<p>El fichero no contiene una imagen</p>';
          echo "</li>";
        }
        echo "</ul>";        
      }
      function is_image($file) {
        $datos = getimagesize($file);
        return $datos ? ((in_array($datos[2], 
          array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_BMP, IMAGETYPE_PNG, IMAGETYPE_BMP))) ? true : false) : false;
      }
  ?>
</body>
</html>
