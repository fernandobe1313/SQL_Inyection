<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $newName = trim($_POST['full_name'] ?? '');

    if ($id === false || $id === null) {
        $message = 'ID inválido. Debe ser un número entero.';
    } elseif ($newName === '') {
        $message = 'El nombre no puede estar vacío.';
    } elseif (mb_strlen($newName) > 120) {
        $message = 'El nombre es demasiado largo.';
    } else {
        // CORREGIDO: UPDATE con parámetros.
        $stmt = $pdo->prepare('UPDATE users SET full_name = :full_name WHERE id = :id');
        $stmt->execute([
            ':full_name' => $newName,
            ':id' => $id
        ]);
        $message = 'Actualización segura ejecutada. Filas afectadas: ' . $stmt->rowCount();
    }
}

$stmt = $pdo->query('SELECT id, username, password, full_name, role FROM users ORDER BY id');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Corregido</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f8; margin:0; }
        .container { max-width: 1000px; margin:30px auto; background:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,.1); }
        h1 { color:#166534; }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        th, td { border:1px solid #ddd; padding:10px; text-align:left; }
        th { background:#dcfce7; }
        input { padding:9px; margin:5px; }
        button { padding:10px 14px; background:#166534; color:white; border:0; border-radius:8px; cursor:pointer; }
        .msg { background:#e0f2fe; padding:10px; border-radius:8px; margin:12px 0; }
        .hint { background:#dcfce7; padding:10px; border-radius:8px; margin-top:15px; }
        a { color:#166534; }
        code { background:#eee; padding:2px 5px; border-radius:4px; }
    </style>
</head>
<body>
<div class="container">
    <p><a href="logout.php">Cerrar sesión</a></p>
    <h1>Panel corregido</h1>
    <p>Sesión iniciada como: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></p>

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

    <h2>Modificar datos de forma segura</h2>
    <?php if ($message): ?>
        <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="id" placeholder="ID, ejemplo: 2" required>
        <input type="text" name="full_name" placeholder="Nuevo nombre" required style="width:360px;">
        <button type="submit">Actualizar</button>
    </form>

    <div class="hint">
        Prueba el ataque anterior:<br>
        ID: <code>1 OR 1=1</code><br>
        Nuevo nombre: <code>Modificado por SQLi</code><br><br>
        Resultado esperado: la app debe rechazarlo porque el ID no es un número entero.
    </div>
</div>
</body>
</html>
