<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "darsival_gym";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Look for user
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: welcome.html"); // redirect to your welcome page
    exit();
  } else {
    echo "<script>alert('Incorrect password.'); window.history.back();</script>";
  }
} else {
  echo "<script>alert('No user found with that email.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
