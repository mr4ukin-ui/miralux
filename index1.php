<?php
session_start();

// Подключаем конфигурацию базы данных
require_once 'config.php';

// Принудительно устанавливаем язык для украинской версии
$_SESSION['language'] = 'uk';
$lang = 'uk';
$texts = [
    'ru' => [
        'reviews_title' => 'Отзывы наших клиентов',
        'all_reviews' => 'Все отзывы'
    ],
    'uk' => [
        'reviews_title' => 'Відгуки наших клієнтів',
        'all_reviews' => 'Усі відгуки'
    ]
];

// Обработка выбора языка
if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'] === 'ru' ? 'ru' : 'uk';
    $redirect_url = $_SESSION['language'] === 'ru' ? '/ru/index.php' : '/index.php';
    header("Location: $redirect_url");
    exit;
}

// Отключаем кэширование
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Світлодіодні дзеркала від MIRALUX: сучасний дизайн, індивідуальне виготовлення, висока якість. Ідеально для ванної, спальні чи салону.">
    <meta name="keywords" content="дзеркала з підсвіткою, LED дзеркала, дизайнерські дзеркала, дзеркала для ванної, MIRALUX">
    <meta name="author" content="MIRALUX">
    <title>Дзеркала з підсвіткою, скляні полиці, двері зі скла | MIRALUX</title>
    <link rel="alternate" hreflang="uk" href="https://miralux.pp.ua/" />
    <link rel="alternate" hreflang="ru" href="https://miralux.pp.ua/ru/" />
    <link rel="alternate" hreflang="x-default" href="https://miralux.pp.ua/" />
    <link rel="stylesheet" href="https://miralux.pp.ua/css/styles.css?v=2">
    <link rel="stylesheet" href="https://miralux.pp.ua/css/slider.css?v=1">
    <link rel="stylesheet" href="https://miralux.pp.ua/css/btn_chat.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'include/header.php'; ?>

    <!-- Главный экран -->
    <section class="hero" id="hero">
        <div class="hero-content">
            <h1>Дзеркала та вироби зі скла для вашого простору</h1>
            <p>Сучасний дизайн і функціональність для дому та бізнесу.</p>
            <a href="#contact" class="btn">
                <i class="fas fa-thin fa-hand-point-right"></i> Замовити зараз
            </a>
        </div>
    </section>

    <!-- О нас -->
    <section class="about" id="about">
        <h2>Чому обирають нас?</h2>
        <p>Ми створюємо унікальні дзеркала та дзеркала з підсвіткою, скляні полиці, які ідеально впишуться в інтер’єр ванної, спальні чи салону. Кожна деталь продумана для вашого комфорту і стилю.</p>
        <div class="features">
            <div><i class="fas fa-industry"></i> Власне виробництво</div>
            <div><i class="fas fa-paint-brush"></i> Індивідуальний дизайн</div>
            <div><i class="fas fa-lightbulb"></i> LED-підсвітка</div>
            <div><i class="fas fa-star"></i> Висока якість</div>
            <div><i class="fas fa-tools"></i> Монтаж</div>
        </div>
    </section>

    <!-- Галерея -->
    <section class="gallery" id="gallery">
        <h2>Наші роботи</h2>
        <div class="gallery-container" id="galleryItems">
            <div class="gallery-item mirrors">
                <div class="gallery-content">
                    <a href="gallery.php?category=mirror">
                        <img src="images/mirror/mirror.webp" alt="Дзеркало" class="gallery-img">
                    </a>
                    <span class="gallery-label">Дзеркала</span>
                </div>
            </div>
            <div class="gallery-item led-mirrors">
                <div class="gallery-content">
                    <a href="gallery.php?category=led_mirror">
                        <img src="images/led_mirror/led_mirror.webp" alt="LED-Дзеркало" class="gallery-img">
                    </a>
                    <span class="gallery-label">LED-дзеркала</span>
                </div>
            </div>
            <div class="gallery-item mirror-tiles">
                <div class="gallery-content">
                    <a href="gallery.php?category=mirror_tiles">
                        <img src="images/mirror_tiles/mirror_tiles.jpg" alt="Дзеркальна плитка" class="gallery-img">
                    </a>
                    <span class="gallery-label">Дзеркальна плитка</span>
                </div>
            </div>
            <div class="gallery-item shelves">
                <div class="gallery-content">
                    <a href="gallery.php?category=shelves">
                        <img src="images/shelves/shelves.jpg" alt="Скляні полиці" class="gallery-img">
                    </a>
                    <span class="gallery-label">Скляні полиці</span>
                </div>
            </div>
            <div class="gallery-item doors">
                <div class="gallery-content">
                    <a href="gallery.php?category=doors">
                        <img src="images/doors/doors.jpg" alt="Скляні двері" class="gallery-img">
                    </a>
                    <span class="gallery-label">Скляні двері</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Отзывы -->
<section class="reviews-section" style="padding: 60px 20px; text-align: center;">
    <h2 style="color: #d4af37; font-size: 2.8em;">
        <?php echo $texts[$lang]['reviews_title']; ?>
    </h2>
    <!-- Swiper Container -->
    <div class="swiper reviews-swiper">
        <div class="swiper-wrapper">
            <?php
            $result = $conn->query("SELECT * FROM reviews WHERE approved = 1 AND lang = '$lang' ORDER BY created_at DESC LIMIT 3");
            while ($row = $result->fetch_assoc()) {
                echo "<div class='swiper-slide'>";
                echo "<div class='review-item'>";
                echo "<div class='stars'>" . str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) . "</div>";
                echo "<p>" . htmlspecialchars($row['text']) . "</p>";
                echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong></p>";
                echo "<p class='city'>" . htmlspecialchars($row['city']) . "</p>";
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
                echo "</div>";
            }
            ?>
        </div>
        <!-- Пагинация -->
        <div class="swiper-pagination"></div>
        <!-- Навигационные стрелки -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
    <a href="reviews.php" class="btn all-reviews-btn">
        <?php echo $texts[$lang]['all_reviews']; ?>
    </a>
</section>

<!-- Подключение swiper CSS и JS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://miralux.pp.ua/js/slider.js"></script>

    <!-- Контакты -->
    <section class="contact" id="contact">
        <h2>Замовити прорахунок</h2>
        <?php include 'contact-form.php'; ?>
        <div class="social-icons">
            <a href="https://t.me/miralux_ua" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
                <i class="fab fa-telegram-plane"></i>
            </a>
            <a href="https://facebook.com/profile.php?id=61574010343420" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com/miralux_ua" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </section>

    <?php include 'include/footer.php'; ?>

    <script src="https://miralux.pp.ua/js/script.js"></script>
    <script>
        // Inline-обработчик для гамбургер-меню
        document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('nav-menu');

            const toggleMenu = () => {
                hamburger.classList.toggle('open');
                navMenu.classList.toggle('open');
            };

            hamburger.addEventListener('click', toggleMenu);
            hamburger.addEventListener('touchstart', toggleMenu);
        });
    </script>
</body>
</html>