<?php
session_start();
require_once 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// VULNERABLE: concatena directamente lo que escribe el usuario.
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";

try {
    $result = $pdo->query($sql);
    $user = $result->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['last_query'] = $sql;
        header('Location: dashboard.php');
        exit;
    }

    header('Location: index.php?error=' . urlencode('Usuario o contraseña incorrectos') . '&query=' . urlencode($sql));
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('Error SQL: ' . $e->getMessage()) . '&query=' . urlencode($sql));
    exit;
}
?>
