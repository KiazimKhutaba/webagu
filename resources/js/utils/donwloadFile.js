function downloadFile(data="", fileName="test.txt", type="text/plain;charset=UTF-8") {

    // создаем невидимый элемент ссылки
    const a = document.createElement("a");
    a.style.display = "none";
    document.body.appendChild(a);

    // в качестве данных для загрузки из ссылки устанавливаем данные из формы
    a.href = window.URL.createObjectURL(
        new Blob([data], { type })
    );

    // аттрибут download ссылки используется для загрузки файла
    a.setAttribute("download", fileName);

    // запускаем загрузку путем эмулирования нажатия клавиши
    a.click();

    // удаляем ссылку из DOM
    document.body.removeChild(a);
}

export default downloadFile;
