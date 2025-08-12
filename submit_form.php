<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы и экранируем их для безопасности
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    // Проверяем, что все поля заполнены
    if (empty($name) || empty($phone) || empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Все поля должны быть заполнены']);
        exit;
    }

    // Формируем сообщение для Telegram
    $telegram_message = "Новая заявка с сайта!\nИмя: $name\nТелефон: $phone\nТекст: $message";

    // Настройки Telegram
    $telegram_token = "7445377490:AAGt9j2x7PSzRVHReiv_Ht4UdYAR-EoPvyY";
    $telegram_chat_id = "7988811309";

    // Кодируем сообщение для URL
    $telegram_message_encoded = urlencode($telegram_message);
    $telegram_api_url = "https://api.telegram.org/bot$telegram_token/sendMessage?chat_id=$telegram_chat_id&text=$telegram_message_encoded";

    // Отправляем запрос в Telegram
    $response = file_get_contents($telegram_api_url);

    // Проверяем успешность отправки
    if ($response === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка отправки в Telegram']);
        exit;
    }

    // Возвращаем успешный ответ в формате JSON для AJAX
    http_response_code(200);
    echo json_encode(['success' => 'Данные успешно отправлены в Telegram!']);
} else {
    // Если метод запроса не POST, возвращаем ошибку
    http_response_code(405);
    echo json_encode(['error' => 'Метод запроса должен быть POST']);
}
?>