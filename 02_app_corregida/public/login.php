<?php
session_start();
require_once 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// CORREGIDO: la consulta preparada separa SQL de datos del usuario.
$sql = 'SELECT * FROM users WHERE username = :username AND password = :password LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':password' => $password
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['user'] = $user;
    header('Location: dashboard.php');
    exit;
}

header('Location: index.php?error=' . urlencode('Usuario o contraseña incorrectos'));
exit;
?>
