USE lumora_cafe_v2;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='recebedor'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN recebedor VARCHAR(100) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='telefone'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN telefone VARCHAR(11) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='cep'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN cep CHAR(8) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='rua'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN rua VARCHAR(150) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='numero'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN numero VARCHAR(20) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='complemento'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN complemento VARCHAR(100) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='bairro'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN bairro VARCHAR(80) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='cidade'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN cidade VARCHAR(80) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='estado'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN estado CHAR(2) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='referencia'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN referencia VARCHAR(150) DEFAULT NULL');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

SET @lumora_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pedidos' AND COLUMN_NAME='atualizado_em'), 'SELECT 1', 'ALTER TABLE pedidos ADD COLUMN atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
PREPARE lumora_stmt FROM @lumora_sql;
EXECUTE lumora_stmt;
DEALLOCATE PREPARE lumora_stmt;

USE lumora_cafe_v2;
CREATE TABLE IF NOT EXISTS mensagens_lidas (
 mensagem_id INT NOT NULL PRIMARY KEY,
 lida_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
