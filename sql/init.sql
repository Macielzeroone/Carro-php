CREATE DATABASE IF NOT EXISTS carros_db;
USE carros_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS carros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(80) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(50),
    placa VARCHAR(10) NOT NULL UNIQUE,
    preco DECIMAL(10,2),
    usuario_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Usuário de exemplo (senha: 123456)
INSERT INTO usuarios (nome, email, senha) VALUES
('Admin', 'admin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE id=id;
