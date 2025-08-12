<?php
session_start();
require_once 'config.php';

$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'uk';
$texts = [
    'ru' => [
        'title' => 'Отзывы | MIRALUX',
        'description' => 'Читайте отзывы клиентов о зеркалах с подсветкой, стеклянных полках и дверях от MIRALUX. Оставьте свой отзыв!',
        'keywords' => 'отзывы MIRALUX, зеркала с подсветкой отзывы, стеклянные полки отзывы',
        'h2' => 'Отзывы наших клиентов',
        'form_name' => 'Ваше имя',
        'form_text' => 'Ваш отзыв',
        'form_rating' => 'Оценка',
        'form_submit' => 'Отправить отзыв',
        'success' => 'Спасибо! Ваш отзыв отправлен на модерацию.',
        'error' => 'Ошибка! Заполните все поля.',
        'captcha_error' => 'Ошибка проверки reCAPTCHA. Попробуйте снова.'
    ],
    'uk' => [
        'title' => 'Відгуки | MIRALUX',
        'description' => 'Читайте відгуки клієнтів про дзеркала з підсвіткою, скляні полиці та двері від MIRALUX. Залиште свій відгук!',
        'keywords' => 'відгуки MIRALUX, дзеркала з підсвіткою відгуки, скляні полиці відгуки',
        'h2' => 'Відгуки наших клієнтів',
        'form_name' => 'Ваше ім’я',
        'form_text' => 'Ваш відгук',
        'form_rating' => 'Оцінка',
        'form_submit' => 'Надіслати відгук',
        'success' => 'Дякуємо! Ваш відгук відправлено на модерацію.',
        'error' => 'Помилка! Заповніть усі поля.',
        'captcha_error' => 'Помилка перевірки reCAPTCHA. Спробуйте ще раз.'
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $text = trim($_POST['text']);
    $rating = (int)$_POST['rating'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Проверка reCAPTCHA
    $secretKey = defined('RECAPTCHA_SECRET_KEY') ? RECAPTCHA_SECRET_KEY : '6Lckny0rAAAAAHhrErxQH5bffFQa1j6oDYF-W9rv'; // Замените или используйте config.php
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $resultJson = json_decode($result);

    if ($resultJson->success && $resultJson->score >= 0.5) {
        if (!empty($name) && !empty($text) && $rating >= 1 && $rating <= 5) {
            // Сохранение отзыва в базу данных
            $stmt = $conn->prepare("INSERT INTO reviews (name, text, rating, lang) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $name, $text, $rating, $lang);
            $stmt->execute();
            $stmt->close();

            // Отправка уведомления в Telegram
            $telegramToken = '8002645023:AAFIPR_vAMO8lydRGS5wS4P9uLtP52G1OoY';
            $chatId = '7988811309';
            $message = "🔔 Новый отзыв на сайте MIRALUX!\n\n";
            $message .= "Имя: " . htmlspecialchars($name) . "\n";
            $message .= "Оценка: " . str_repeat("⭐", $rating) . "\n";
            $message .= "Текст: " . htmlspecialchars($text) . "\n";
            $message .= "Язык: " . ($lang === 'ru' ? 'Русский' : 'Украинский') . "\n";
            $message .= "Одобрить: https://miralux.pp.ua/admin/reviews.php";

            $url = "https://api.telegram.org/bot$telegramToken/sendMessage";
            $data = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ],
            ];
            $context = stream_context_create($options);
            file_get_contents($url, false, $context);

            $success = $texts[$lang]['success'];
        } else {
            $error = $texts[$lang]['error'];
        }
    } else {
        $error = $texts[$lang]['captcha_error'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $texts[$lang]['description']; ?>">
    <meta name="keywords" content="<?php echo $texts[$lang]['keywords']; ?>">
    <meta name="author" content="MIRALUX">
    <title><?php echo $texts[$lang]['title']; ?></title>
    <link rel="stylesheet" href="https://miralux.pp.ua/css/styles.css?v=2">
    <link rel="stylesheet" href="https://miralux.pp.ua/css/btn_chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo defined('RECAPTCHA_SITE_KEY') ? RECAPTCHA_SITE_KEY : '6Lckny0rAAAAAF1O6LYqngQaazmRafq4BVopT3cx'; ?>"></script>
    <style>
        .reviews-section { padding: 120px 20px; text-align: center; }
        .reviews-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 40px; }
        .review-item { 
            background: #252525; 
            padding: 20px; 
            border-radius: 10px; 
            color: #fff; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }
        .review-item .stars { color: #d4af37; margin-bottom: 10px; font-size: 1.2em; }
        .review-form { max-width: 500px; margin: 40px auto; }
        .review-form input, .review-form textarea, .review-form select { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #8f8d8d91; 
            border-radius: 5px; 
            background: #1a1a1a; 
            color: #fff; 
            font-family: 'Poppins', sans-serif;
        }
        .review-form textarea { resize: vertical; min-height: 100px; }
        .review-form button { 
            background: #d4af37; 
            color: #1a1a1a; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .review-form button:hover { background: #00b4d8; color: #fff; transform: scale(1.05); }
        .success-message, .error-message { 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 5px; 
            text-align: center; 
        }
        .success-message { background: #d4af37; color: #1a1a1a; }
        .error-message { background: #ff5555; color: #fff; }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.review-form').addEventListener('submit', (e) => {
                e.preventDefault();
                grecaptcha.ready(() => {
                    grecaptcha.execute('<?php echo defined('RECAPTCHA_SITE_KEY') ? RECAPTCHA_SITE_KEY : '6Lckny0rAAAAAF1O6LYqngQaazmRafq4BVopT3cx'; ?>', {action: 'submit_review'}).then((token) => {
                        document.querySelector('input[name="g-recaptcha-response"]').value = token;
                        e.target.submit();
                    });
                });
            });
        });
    </script>
</head>
<body>
    <?php include 'include/header.php'; ?>

    <section class="reviews-section">
        <h2 style="color: #d4af37; font-size: 2.8em;"><?php echo $texts[$lang]['h2']; ?></h2>

        <!-- Форма для отзывов -->
        <form class="review-form" method="POST">
            <input type="text" name="name" placeholder="<?php echo $texts[$lang]['form_name']; ?>" required>
            <textarea name="text" placeholder="<?php echo $texts[$lang]['form_text']; ?>" required></textarea>
            <select name="rating" required>
                <option value=""><?php echo $texts[$lang]['form_rating']; ?></option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★</option>
                <option value="3">★★★</option>
                <option value="2">★★</option>
                <option value="1">★</option>
            </select>
            <input type="hidden" name="g-recaptcha-response">
            <button type="submit"><i class="fas fa-paper-plane"></i> <?php echo $texts[$lang]['form_submit']; ?></button>
        </form>

        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <!-- Список отзывов -->
        <div class="reviews-grid">
            <?php
            $result = $conn->query("SELECT * FROM reviews WHERE approved = 1 AND lang = '$lang' ORDER BY created_at DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review-item'>";
                echo "<div class='stars'>" . str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) . "</div>";
                echo "<p>" . htmlspecialchars($row['text']) . "</p>";
                echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong></p>";
                echo "<script type='application/ld+json'>";
                echo json_encode([
                    "@context" => "https://schema.org",
                    "@type" => "Review",
                    "author" => [
                        "@type" => "Person",
                        "name" => htmlspecialchars($row['name'])
                    ],
                    "reviewRating" => [
                        "@type" => "Rating",
                        "ratingValue" => $row['rating'],
                        "bestRating" => 5
                    ],
                    "reviewBody" => htmlspecialchars($row['text']),
                    "itemReviewed" => [
                        "@type" => "Organization",
                        "name" => "MIRALUX"
                    ]
                ], JSON_UNESCAPED_UNICODE);
                echo "</script>";
                echo "</div>";
            }
            ?>
        </div>
    </section>

    <?php include 'include/footer.php'; ?>
    <script src="https://miralux.pp.ua/js/script.js"></script>
</body>
</html>