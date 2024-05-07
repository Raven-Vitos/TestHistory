document.addEventListener("DOMContentLoaded", function () {
    let loadData = getDataFromCookie("data");

    if (loadData != "") {
        loadData = JSON.parse(loadData);
    } else {
        loadData = [];
    }

    let mid_val = 0;
    let num_q = 0;
    for (var i in loadData) {
        let item = loadData[i];
        if (item["value"] > mid_val) {
            num_q++;
            mid_val += parseInt(item["value"]);
        }
    }
    mid_val /= num_q;
    mid_val = Math.ceil(mid_val);

    num_q = 0;
    var table = document.getElementById("table-data");
    for (var i in loadData) {
        let item = loadData[i];
        if (item["value"] >= mid_val) {
            num_q++;
            var newRow = document.createElement("tr");
            newRow.setAttribute("onclick", "show_q('Вопрос " + item["id"] + ". ')");
            newRow.setAttribute("data-target", "#exampleModal");
            newRow.setAttribute("data-toggle", "modal");

            // Создаем ячейки и добавляем их в строку
            var cell1 = document.createElement("td");
            var cell2 = document.createElement("td");
            var cell3 = document.createElement("td");

            // Устанавливаем текстовое содержимое ячеек
            cell1.textContent = num_q;
            cell2.textContent = "Колличество ошибок в вопросе №" + item["id"];
            cell3.textContent = item["value"];

            // Добавляем ячейки в строку
            newRow.appendChild(cell1);
            newRow.appendChild(cell2);
            newRow.appendChild(cell3);

            // Добавляем новую строку в таблицу
            table.appendChild(newRow);
        }
    }
});

// Получаем ссылку на кнопку по её id и добавляем обработчик события при клике на неё
document.getElementById("go-back").addEventListener("click", function () {
    // Изменяем URL-адрес текущего окна на нужную ссылку
    window.location.href = "http://192.168.1.125:3000/"; // Замените 'http://example.com' на нужную вам ссылку
});

async function show_q(info) {
    try {
        // Загружаем данные из JSON-файла
        const response = await fetch("questions.json");
        const data = await response.json();

        data.forEach((question) => {
            if (question["title"] == info) {
                let text = "Вопрос: " + question["description"] + "<br><br>Ответ: " + question["answer"];
                // alert(text);
                document.getElementById("info-modal").innerHTML = text;
                document.getElementById("exampleModalLabel").innerHTML = question["title"];
            }
        });
    } catch (error) {
        console.error("Ошибка загрузки данных:", error);
    }
}

function clear_all_cookies() {
    clearCookie("data");
    alert("Данные об ошибках удалены!");
    location.reload();
}
