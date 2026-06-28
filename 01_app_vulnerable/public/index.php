<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}
$error = $_GET['error'] ?? '';
$query = $_GET['query'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SQLi Lab - App Vulnerable</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f8; margin:0; }
        .box { max-width: 430px; margin: 70px auto; background:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,.1); }
        h1 { margin-top:0; color:#b91c1c; }
        label { display:block; margin-top:12px; font-weight:bold; }
        input { width:100%; padding:10px; margin-top:5px; box-sizing:border-box; }
        button { margin-top:18px; width:100%; padding:12px; background:#b91c1c; color:white; border:0; border-radius:8px; cursor:pointer; }
        .error { background:#fee2e2; padding:10px; border-radius:8px; margin-bottom:12px; }
        .query { background:#111827; color:#d1fae5; padding:10px; border-radius:8px; font-size:13px; overflow:auto; }
        .hint { background:#fef3c7; padding:10px; border-radius:8px; font-size:14px; }
        code { background:#eee; padding:2px 5px; border-radius:4px; }
    </style>
</head>
<body>
<div class="box">
    <h1>App Vulnerable</h1>
    <p>Laboratorio local de SQL Injection con PHP + MySQL + Docker.</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($query): ?>
        <p><strong>Consulta ejecutada:</strong></p>
        <div class="query"><?= htmlspecialchars($query) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label>Usuario</label>
        <input type="text" name="username" placeholder="admin" required>

        <label>Contraseña</label>
        <input type="text" name="password" placeholder="admin123" required>

        <button type="submit">Iniciar sesión</button>
    </form>

    <div class="hint" style="margin-top:18px;">
        Prueba de laboratorio:<br>
        Usuario: <code>' OR '1'='1' -- </code><br>
        Contraseña: <code>123</code>
    </div>
</div>
</body>
</html>
