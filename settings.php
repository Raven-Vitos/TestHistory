<?php
    // Получение данных из POST-запроса
    $data = json_decode(file_get_contents('php://input'), true);

    // Если данные присутствуют, сохраняем их в файл
    if ($data) {
        $allQ = $data['all'];
        $minQ = $data['min'];
        $maxQ = $data['max'];

        save_data($allQ, $minQ, $maxQ);
        echo 'Сохранение успешно завершено.';
    } else {
    echo 'Данные не найдены.';
    }

function save_data($allQ, $minQ, $maxQ) {
    $data = $allQ . ';' . $minQ . ';' . $maxQ; // Данные для записи

    $filename = "config.ini"; // Имя файла

    // Записываем данные в файл
    file_put_contents($filename, $data);

    echo "Данные успешно записаны в файл.";
}