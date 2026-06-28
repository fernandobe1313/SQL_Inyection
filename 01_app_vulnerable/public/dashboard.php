<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$message = '';
$updateQuery = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $newName = $_POST['full_name'] ?? '';

    // VULNERABLE: el ID y el nombre se meten directo en el UPDATE.
    $updateQuery = "UPDATE users SET full_name = '$newName' WHERE id = $id";

    try {
        $affected = $pdo->exec($updateQuery);
        $message = "Actualización ejecutada. Filas afectadas: $affected";
    } catch (PDOException $e) {
        $message = 'Error SQL: ' . $e->getMessage();
    }
}

$users = $pdo->query('SELECT id, username, password, full_name, role FROM users ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
$lastQuery = $_SESSION['last_query'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Vulnerable</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f8; margin:0; }
        .container { max-width: 1000px; margin:30px auto; background:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,.1); }
        h1 { color:#b91c1c; }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        th, td { border:1px solid #ddd; padding:10px; text-align:left; }
        th { background:#fee2e2; }
        input { padding:9px; margin:5px; }
        button { padding:10px 14px; background:#b91c1c; color:white; border:0; border-radius:8px; cursor:pointer; }
        .query { background:#111827; color:#d1fae5; padding:10px; border-radius:8px; font-size:13px; overflow:auto; }
        .msg { background:#e0f2fe; padding:10px; border-radius:8px; margin:12px 0; }
        .hint { background:#fef3c7; padding:10px; border-radius:8px; margin-top:15px; }
        a { color:#b91c1c; }
        code { background:#eee; padding:2px 5px; border-radius:4px; }
    </style>
</head>
<body>
<div class="container">
    <p><a href="logout.php">Cerrar sesión</a></p>
    <h1>Panel vulnerable</h1>
    <p>Sesión iniciada como: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></p>

    <?php if ($lastQuery): ?>
        <p><strong>Consulta usada para entrar:</strong></p>
        <div class="query"><?= htmlspecialchars($lastQuery) ?></div>
    <?php endif; ?>

    <h2>Usuarios en MySQL</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Contraseña</th>
            <th>Nombre</th>
            <th>Rol</th>
        </tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['password']) ?></td>
                <td><?= htmlspecialchars($u['full_name']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Modificar datos</h2>
    <?php if ($message): ?>
        <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($updateQuery): ?>
        <p><strong>Consulta UPDATE ejecutada:</strong></p>
        <div class="query"><?= htmlspecialchars($updateQuery) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="id" placeholder="ID, ejemplo: 2" required>
        <input type="text" name="full_name" placeholder="Nuevo nombre" required style="width:360px;">
        <button type="submit">Actualizar</button>
    </form>

    <div class="hint">
        Prueba normal:<br>
        ID: <code>2</code> | Nuevo nombre: <code>Kevin actualizado</code><br><br>
        SQLi para cambiar nombre y rol:<br>
        ID: <code>2</code> | Nuevo nombre: <code>Nombre cambiado', role='admin</code><br><br>
        SQLi para afectar varios registros:<br>
        ID: <code>1 OR 1=1</code> | Nuevo nombre: <code>Modificado por SQLi</code>
    </div>
</div>
</body>
</html>
