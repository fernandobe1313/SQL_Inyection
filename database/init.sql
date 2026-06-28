CREATE DATABASE IF NOT EXISTS sqli_vulnerable CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS sqli_corregida CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON sqli_vulnerable.* TO 'labuser'@'%';
GRANT ALL PRIVILEGES ON sqli_corregida.* TO 'labuser'@'%';
FLUSH PRIVILEGES;

USE sqli_vulnerable;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  full_name VARCHAR(120) NOT NULL,
  role VARCHAR(30) NOT NULL
);

INSERT INTO users (username, password, full_name, role) VALUES
('admin', 'admin123', 'Administrador del sistema', 'admin'),
('kevin', '12345', 'Kevin Anthony Ramos', 'student'),
('aaron', 'upq2026', 'Aarón Sánchez Cervantes', 'student'),
('profesor', 'profe123', 'Profesor de Seguridad', 'teacher');

USE sqli_corregida;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  full_name VARCHAR(120) NOT NULL,
  role VARCHAR(30) NOT NULL
);

INSERT INTO users (username, password, full_name, role) VALUES
('admin', 'admin123', 'Administrador del sistema', 'admin'),
('kevin', '12345', 'Kevin Anthony Ramos', 'student'),
('aaron', 'upq2026', 'Aarón Sánchez Cervantes', 'student'),
('profesor', 'profe123', 'Profesor de Seguridad', 'teacher');
