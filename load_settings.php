<?php
    $filename = "config.ini"; // Имя файла

    // Читаем данные из файла
    $data = file_get_contents($filename);

    echo $data;
?>