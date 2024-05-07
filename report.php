<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестирование знаний</title>
    <!-- Подключаем Bootstrap CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">
  </head>
<body style="background-color: rgb(238, 238, 238);">  
    
    <div id="cardsContainer" style="width: 60%; margin: auto;">
    <h1 class="text-center mt-3">Отчет</h1>
        <div class="card m-3"> 
            <div class="card-body">
                <table class="table table-dark table-striped m-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Информация</th>
                            <th scope="col">Колличество ошибок</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            // Считываем содержимое файла data.json
                            $jsonData = file_get_contents('data.json');

                            // Декодируем JSON-данные в массив PHP
                            $dataArray = json_decode($jsonData, true);

                            $mid_val = 0;
                            $num_q = 0;
                            foreach ($dataArray as &$item) {  
                                if ($item['value'] > $mid) {
                                    $num_q ++;                              
                                    $mid_val += intval($item['value']);
                                }
                            }
                            $mid_val /= $num_q;
                            $mid_val = ceil($mid_val);

                            // Ищем объект с нужным id
                            $num_q = 0;
                            foreach ($dataArray as &$item) {                                
                                if ($item['value'] > $mid_val) {
                                    $num_q ++;

                                    echo "<tr onclick=\"show_q('Вопрос " . $item['id'] . ". ')\" data-toggle=\"modal\" data-target=\"#exampleModal\">";
                                        echo '<td>' . $num_q . '</td>';
                                        echo '<td>Колличество ошибок в вопросе №' . $item['id'] . '</td>';
                                        echo '<td>' . $item["value"] . '</td>';
                                    echo '</tr>';
                                }

                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-dark m-3" id="go-back">Вернуться назад к тестированию</button>
        </div>        
    </div>

    
    <!-- Подключаем Bootstrap JS и скрипт для обработки ответов -->

    <script>
        // Получаем ссылку на кнопку по её id и добавляем обработчик события при клике на неё
        document.getElementById('go-back').addEventListener('click', function() {
        // Изменяем URL-адрес текущего окна на нужную ссылку
        window.location.href = 'http://192.168.1.125:3000/'; // Замените 'http://example.com' на нужную вам ссылку
        });


        async function show_q(info) {
            try {
                // Загружаем данные из JSON-файла
                const response = await fetch("questions.json");
                const data = await response.json();

                data.forEach((question) => {
                    if (question["title"] == info)
                    {
                        let text = "Вопрос: " + question["description"] + '<br><br>Ответ: ' + question["answer"];
                        //alert(text);
                        document.getElementById("info-modal").innerHTML = text;
                        document.getElementById("exampleModalLabel").innerHTML = question["title"];
                    }
                });
            } catch (error) {
                console.error("Ошибка загрузки данных:", error);
            }
        }
    </script>

    <!-- Модальное окно -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background: #e2e3e5;">
                <h5 class="modal-title" id="exampleModalLabel">Модальное окно</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body" id="info-modal">
                Информация в модальном окне.
            </div>
            <div class="modal-footer" style="background: #e2e3e5;">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            </div>
            </div>
        </div>
    </div>


    <script src="jquery-3.5.1.slim.min.js"></script>
    <script src="popper.min.js"></script>
    <script src="bootstrap.min.js"></script>
  </body>
</html>
