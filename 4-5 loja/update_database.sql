-- Script para atualizar a estrutura do banco de dados
-- para armazenar imagens diretamente como BLOB

USE loja_jogos;

-- Modificar a coluna IMG para LONGBLOB para armazenar imagens diretamente
ALTER TABLE produto MODIFY COLUMN IMG LONGBLOB;

-- Verificar a estrutura atualizada
DESCRIBE produto;
