# SQL Injection

## Qué es una inyección SQL

Una inyección SQL es una vulnerabilidad que ocurre cuando una aplicación web coloca directamente los datos que escribe el usuario dentro de una consulta SQL. El problema es que el sistema no separa correctamente los datos del usuario y el código SQL. Por eso, una persona puede escribir una entrada manipulada para cambiar el comportamiento de la consulta.

Por ejemplo, si una app tiene un login y arma la consulta así:

```php
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

la aplicación está tomando el usuario y la contraseña directamente desde el formulario. Si alguien escribe una entrada especial, puede hacer que la consulta cambie.

## Cómo se hace en laboratorio

En esta práctica se usa una app vulnerable hecha con PHP, MySQL y Docker. La app tiene un formulario de login. En vez de escribir un usuario normal, se escribe:

```txt
' OR '1'='1' -- 
```

Y en contraseña se puede escribir cualquier cosa:

```txt
123
```

Esto altera la consulta porque agrega una condición que siempre es verdadera:

```sql
OR '1'='1'
```

El símbolo de comentario `--` hace que el resto de la consulta no se tome en cuenta.

## Efectos que puede tener

Una SQL Injection puede causar varios problemas:

- Acceso no autorizado a una cuenta.
- Lectura de datos privados.
- Modificación de registros.
- Eliminación de información.
- Cambio de roles o permisos.
- Pérdida de confianza en el sistema.

En esta práctica se muestran dos efectos principales: entrar sin contraseña real y modificar datos dentro de la base de datos.

## Ejemplo real o de laboratorio

En la app vulnerable se puede entrar con:

```txt
Usuario: ' OR '1'='1' -- 
Contraseña: 123
```

También se puede modificar un usuario desde el panel. Por ejemplo:

```txt
ID: 2
Nuevo nombre: Nombre cambiado', role='admin
```

La consulta vulnerable es:

```php
$updateQuery = "UPDATE users SET full_name = '$newName' WHERE id = $id";
```

Como los datos se concatenan directamente, el valor escrito en el formulario puede cambiar la consulta original.

## Cómo se puede mitigar

La forma principal de evitar una SQL Injection es usar consultas preparadas. Las consultas preparadas separan el código SQL de los datos del usuario.

Ejemplo corregido:

```php
$sql = 'SELECT * FROM users WHERE username = :username AND password = :password LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':password' => $password
]);
```

También se debe validar la información recibida. Por ejemplo, si se espera un ID numérico, se debe validar que realmente sea un número entero:

```php
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
```

Con esto, una entrada como:

```txt
1 OR 1=1
```

ya no se acepta como ID válido.

## Conclusión

La SQL Injection es una vulnerabilidad peligrosa porque permite manipular consultas SQL desde una entrada de usuario. En la práctica se comprobó que una app vulnerable permite entrar sin contraseña y modificar datos de MySQL. Después se corrigió la aplicación usando consultas preparadas y validación de datos. Con esto, el ataque dejó de funcionar y la consulta SQL ya no pudo ser alterada por el usuario.
