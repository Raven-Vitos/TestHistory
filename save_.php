<?php
    // Получение данных из POST-запроса
    $str = "[{\"id\":\"10\",\"value\":0},{\"id\":\"62\",\"value\":0}]";
    $data = json_decode($str, true);

    // Если данные присутствуют, сохраняем их в файл
    if ($data) {
        //$filename = 'data.json';
        //file_put_contents($filename, json_encode($data));
        foreach ($data as $item) {
            $ids = $item['id'];
            update_data($ids);
        }
        echo 'Сохранение успешно завершено.';
    } else {
    echo 'Данные не найдены.';
    }


function update_data($idToIncrement) {
    // Считываем содержимое файла data.json
    $jsonData = file_get_contents('data.json');

    // Декодируем JSON-данные в массив PHP
    $dataArray = json_decode($jsonData, true);

    // Ищем объект с нужным id
    foreach ($dataArray as &$item) {
        
        if ($item['id'] == $idToIncrement) {
            // Увеличиваем значение value на 1
            $item['value']++;
            //echo $item['value'].'<br>';
            //echo ($item['id'] == $idToIncrement) .'<br>';

            break; // Прерываем цикл, если объект найден
        }
    }

    // Кодируем массив обратно в JSON с указанием кодировки UTF-8
    $newJsonData = json_encode($dataArray);

    // Записываем обновленные данные обратно в файл, указывая кодировку UTF-8
    file_put_contents('data.json', $newJsonData);
}