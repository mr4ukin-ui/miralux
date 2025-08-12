<?php
session_start();

require_once 'config.php';

$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'uk';
$texts = [
    'uk' => [
        'title' => 'Галерея | MIRALUX',
        'description' => 'Галерея виробів від MIRALUX: дзеркала з підсвіткою, скляні полиці, двері зі скла. Ознайомтесь з нашими роботами за категоріями.',
        'keywords' => 'дзеркала з підсвіткою, скляні полиці, двері зі скла, галерея MIRALUX',
        'h2' => 'Галерея',
        'filter_all' => 'Усі',
        'filter_mirror' => 'Дзеркала',
        'filter_led_mirror' => 'LED-дзеркала',
        'filter_mirror_tiles' => 'Дзеркальна плитка',
        'filter_shelves' => 'Скляні полиці',
        'filter_doors' => 'Скляні двері'
    ],
    'ru' => [
        'title' => 'Галерея | MIRALUX',
        'description' => 'Галерея изделий от MIRALUX: зеркала с подсветкой, стеклянные полки, стеклянные двери. Ознакомьтесь с нашими работами по категориям.',
        'keywords' => 'зеркала с подсветкой, стеклянные полки, стеклянные двери, галерея MIRALUX',
        'h2' => 'Галерея',
        'filter_all' => 'Все',
        'filter_mirror' => 'Зеркала',
        'filter_led_mirror' => 'LED-зеркала',
        'filter_mirror_tiles' => 'Зеркальная плитка',
        'filter_shelves' => 'Стеклянные полки',
        'filter_doors' => 'Стеклянные двери'
    ]
];
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
    <link rel="shortcut icon" href="https://miralux.pp.ua/favicon48.ico" type="image/x-icon">
    <link rel="icon" href="https://miralux.pp.ua/favicon48.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'include/header.php'; ?>

    <!-- Галерея с категориями -->
    <section class="gallery-page" id="gallery" style="padding-top: 120px;">
        <div class="gallery-wrapper">
            <h2 style="color: #d4af37; font-size: 2.8em; text-align: center; margin-bottom: 30px;"><?php echo $texts[$lang]['h2']; ?></h2>

            <div class="category-buttons" style="text-align: center; margin-bottom: 40px;">
                <button class="btn filter-btn active" data-filter="all"><?php echo $texts[$lang]['filter_all']; ?></button>
                <button class="btn filter-btn" data-filter="mirror"><?php echo $texts[$lang]['filter_mirror']; ?></button>
                <button class="btn filter-btn" data-filter="led_mirror"><?php echo $texts[$lang]['filter_led_mirror']; ?></button>
                <button class="btn filter-btn" data-filter="mirror_tiles"><?php echo $texts[$lang]['filter_mirror_tiles']; ?></button>
                <button class="btn filter-btn" data-filter="shelves"><?php echo $texts[$lang]['filter_shelves']; ?></button>
                <button class="btn filter-btn" data-filter="doors"><?php echo $texts[$lang]['filter_doors']; ?></button>
            </div>

            <div class="gallery-grid" id="galleryItems">
                <?php
                $result = $conn->query("SELECT gi.*, c.slug FROM gallery_images gi JOIN categories c ON gi.category_id = c.id ORDER BY gi.uploaded_at DESC");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='gallery-item {$row['slug']}'>";
                    echo "<img src='images/{$row['file_name']}' alt='{$row['label']}' class='gallery-img'>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Модальное окно -->
    <div class="modal" id="imageModal">
        <span class="modal-close" id="modalClose">×</span>
        <img class="modal-content" id="modalImage">
    </div>

    <?php include 'include/footer.php'; ?>

    <script src="https://miralux.pp.ua/js/script.js"></script>
</body>
</html>