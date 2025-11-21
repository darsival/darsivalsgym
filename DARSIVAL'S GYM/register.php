<?php
// Отключаем предупреждения
error_reporting(0);
ini_set('display_errors', 0);

$host = "localhost";
$user = "root";
$password = "";
$dbname = "darsival_gym";

// Подключение к базе данных
$conn = @new mysqli($host, $user, $password, $dbname);

// Проверка соединения
if ($conn->connect_errno) {
  // Просто выводим содержимое error_server.html вместо редиректа
  echo file_get_contents("error_server.html");
  exit();
}

// Получаем данные формы
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm-password'] ?? '';

// Проверка совпадения паролей
if ($password !== $confirmPassword) {
  echo "Passwords do not match.";
  exit();
}

// Хеширование пароля
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Вставка данных в базу
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Проверка успешной подготовки запроса
if (!$stmt) {
  echo file_get_contents("error_server.html");
  exit();
}

$stmt->bind_param("sss", $name, $email, $hashedPassword);

// Проверка выполнения запроса
if ($stmt->execute()) {
  echo "Registration successful!";
} else {
  echo file_get_contents("error_server.html");
  exit();
}

$stmt->close();
$conn->close();
?>
