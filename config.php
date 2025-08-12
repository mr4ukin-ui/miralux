<?php
$host = 'localhost';
$dbname = 'miral218_miralux';
$username = 'miral218_admin'; // Замени на своего пользователя MySQL
$password = 'Vbirf2007';     // Замени на свой пароль MySQL

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>