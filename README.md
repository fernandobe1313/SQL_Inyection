# Laboratorio SQL Injection con Docker + MySQL

Este proyecto contiene una práctica local de SQL Injection usando:

- Docker
- MySQL 8.0
- PHP 8.2 + Apache
- PDO MySQL

Incluye dos aplicaciones:

- `01_app_vulnerable`: app vulnerable a SQL Injection.
- `02_app_corregida`: misma app corregida con consultas preparadas y validación.

La práctica es únicamente para laboratorio local y aprendizaje.

---

## 1. Requisitos

Solo necesitas tener instalado:

- Docker Desktop
- Git, solo si vas a subirlo a GitHub

No necesitas XAMPP, Laragon, MySQL local ni phpMyAdmin.

---

## 2. Ejecutar el laboratorio

Abre una terminal dentro de la carpeta del proyecto y ejecuta:

```bash
docker compose up --build
```

Espera a que Docker descargue las imágenes y cree los contenedores.

---

## 3. Abrir las aplicaciones

App vulnerable:

```txt
http://localhost:8000
```

App corregida:

```txt
http://localhost:8001
```

MySQL queda disponible en tu máquina en el puerto:

```txt
localhost:3307
```

Datos de conexión MySQL:

```txt
Host: localhost
Puerto: 3307
Usuario: labuser
Contraseña: labpass
Base vulnerable: sqli_vulnerable
Base corregida: sqli_corregida
```

---

## 4. Usuarios de prueba

```txt
Usuario: admin
Contraseña: admin123
```

```txt
Usuario: kevin
Contraseña: 12345
```

```txt
Usuario: aaron
Contraseña: upq2026
```

```txt
Usuario: profesor
Contraseña: profe123
```

---

## 5. SQL Injection en el login vulnerable

Abre:

```txt
http://localhost:8000
```

En usuario escribe:

```txt
' OR '1'='1' -- -    ó tambien puedes usar    ' OR 1=1 # 
```

En contraseña escribe:

```txt
123
```

Resultado esperado:

La app vulnerable permite entrar sin conocer una contraseña real.

---

## 6. Modificar la base de datos en la app vulnerable

Dentro del panel vulnerable puedes probar una actualización normal:

```txt
ID: 2
Nuevo nombre: Kevin actualizado
```

Después prueba una inyección para cambiar también el rol:

```txt
ID: 2
Nuevo nombre: Nombre cambiado', role='admin
```

También puedes probar una inyección para afectar varios registros:

```txt
ID: 1 OR 1=1
Nuevo nombre: Modificado por SQLi
```

Esto funciona porque el código vulnerable construye el SQL concatenando datos del usuario:

```php
$sql = "UPDATE users SET full_name = '$newName' WHERE id = $id";
```

---

## 7. Probar la versión corregida

Abre:

```txt
http://localhost:8001
```

Intenta entrar con el mismo ataque:

```txt
Usuario: ' OR '1'='1' -- 
Contraseña: 123
```

Resultado esperado:

La app corregida no permite entrar.

También prueba en el formulario de actualización:

```txt
ID: 1 OR 1=1
Nuevo nombre: Modificado por SQLi
```

Resultado esperado:

La app corregida debe rechazar el ID porque no es un número entero.

---

## 8. Por qué la versión corregida es segura

La versión corregida usa consultas preparadas:

```php
$sql = 'SELECT * FROM users WHERE username = :username AND password = :password LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':password' => $password
]);
```

También valida el ID:

```php
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
```

Así la entrada del usuario se trata como dato y no como parte del código SQL.

---

## 9. Detener el laboratorio

En la terminal donde está corriendo Docker presiona:

```txt
Ctrl + C
```

Luego ejecuta:

```bash
docker compose down
```

---

## 10. Reiniciar la base de datos desde cero

Si ya modificaste usuarios y quieres dejar todo como al inicio, ejecuta:

```bash
docker compose down -v
```

Luego vuelve a levantar el proyecto:

```bash
docker compose up --build
```

El `-v` borra el volumen de MySQL y permite que se vuelva a ejecutar `database/init.sql`.

---

## 11. Subir a GitHub

Primero sube la versión vulnerable:

```bash
git init
git add README.md docker-compose.yml .gitignore docker database docs 01_app_vulnerable
git commit -m "Version vulnerable para laboratorio SQLi con Docker y MySQL"
```

Luego sube la versión corregida:

```bash
git add 02_app_corregida
git commit -m "Version corregida con consultas preparadas y validacion"
```

Después conecta tu repositorio:

```bash
git branch -M main
git remote add origin https://github.com/TU_USUARIO/TU_REPOSITORIO.git
git push -u origin main
```

Cambia `TU_USUARIO/TU_REPOSITORIO` por tu repositorio real.
