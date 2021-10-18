<?php
    $path    = './output';
    $files = scandir($path);
    $now   = time();

  foreach ($files as $file) {
       
    if (is_file("./output/$file")) {

      if ($now - filemtime("./output/$file") >= 60 * 60 * 24 ) { // 2 days
        unlink("./output/$file");
        echo $file;
        echo "<br>";
      }
    }
  }
?>