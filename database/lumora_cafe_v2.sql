-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/10/2026 às 05:18
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lumora_cafe_v2`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `nome_produto` varchar(150) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `pedido_id`, `produto_id`, `nome_produto`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 2, 'Cappuccino Cremoso', 20, 12.00),
(2, 2, 1, 'Espresso Tradicional', 20, 7.00),
(3, 3, 4, 'Brownie de Chocolate', 20, 11.00),
(4, 4, 2, 'Cappuccino Cremoso', 2, 12.00),
(5, 5, 1, 'Espresso Tradicional', 2, 7.00),
(6, 6, 4, 'Brownie de Chocolate', 3, 11.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `assunto` varchar(150) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `nome`, `email`, `assunto`, `mensagem`, `data_envio`) VALUES
(1, 'caila', 'caila@gmail.com', 'reclamaçao', 'tava tudo um lixo gostei de nada', '2026-09-26 18:45:07'),
(2, 'bruno silva', 'brunosilva@gmail.com', 'cafe tava muito bom ,gostei demais', 'ghggggggggggggggggggggggggggggg', '2026-10-10 20:45:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_lidas`
--

CREATE TABLE `mensagens_lidas` (
  `mensagem_id` int(11) NOT NULL,
  `lida_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens_lidas`
--

INSERT INTO `mensagens_lidas` (`mensagem_id`, `lida_em`) VALUES
(1, '2026-10-11 02:45:12'),
(2, '2026-10-11 02:45:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `tipo_entrega` enum('retirada','delivery') NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `observacoes` varchar(500) DEFAULT NULL,
  `forma_pagamento` enum('na_retirada','na_entrega') NOT NULL,
  `status` enum('recebido','em_preparo','pronto','entregue','cancelado') NOT NULL DEFAULT 'recebido',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `recebedor` varchar(100) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `cep` char(8) DEFAULT NULL,
  `rua` varchar(150) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(80) DEFAULT NULL,
  `cidade` varchar(80) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `referencia` varchar(150) DEFAULT NULL,
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `tipo_entrega`, `endereco`, `observacoes`, `forma_pagamento`, `status`, `criado_em`, `recebedor`, `telefone`, `cep`, `rua`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `referencia`, `atualizado_em`) VALUES
(1, 1, 240.00, 'retirada', '', 'lucas vai buscar', 'na_retirada', 'recebido', '2026-09-26 18:29:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-11 02:42:01'),
(2, 2, 140.00, 'retirada', '', 'eu vou pagar', 'na_retirada', 'entregue', '2026-09-26 18:36:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-11 02:42:01'),
(3, 3, 220.00, 'retirada', '', 'lllllllllllll', 'na_retirada', 'pronto', '2026-09-26 18:38:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-11 02:42:01'),
(4, 2, 24.00, 'delivery', 'Rua Pilar do Sul, 222, Jardim Helena - São Paulo/SP', '55555555555555', 'na_entrega', 'entregue', '2026-10-10 20:47:03', 'Carlens Romelus', '11954783565', '08420600', 'Rua Pilar do Sul', '222', '5', 'Jardim Helena', 'São Paulo', 'SP', '5555555555', '2026-10-11 02:42:01'),
(5, 1, 14.00, 'delivery', 'Rua Pilar do Sul, 22, JARDIM HELENA - São Paulo/SP', 'hhhhhhhhhhhhhhhhhhhhhhhhhhh', 'na_entrega', 'entregue', '2026-10-10 21:03:16', 'caleb', '11954783565', '08420600', 'Rua Pilar do Sul', '22', '2', 'JARDIM HELENA', 'São Paulo', 'SP', '2', '2026-10-11 02:45:29'),
(6, 2, 33.00, 'delivery', 'Rua Pilar do Sul, 11, Jardim Helena - São Paulo/SP', '[´´´´´´´´´´´´´´´´´´', 'na_entrega', 'entregue', '2026-10-11 02:11:34', 'Bruno Carvalho', '11954783565', '08420600', 'Rua Pilar do Sul', '11', '1', 'Jardim Helena', 'São Paulo', 'SP', '´[[[[[[[[[', '2026-10-11 02:45:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `icone` varchar(10) DEFAULT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(60) NOT NULL DEFAULT 'Cafés',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `icone`, `estoque`, `categoria`, `ativo`, `criado_em`) VALUES
(1, 'Espresso Tradicional', 'Café intenso e aromático.', 7.00, 'img/1.jpg', '☕', 98, 'Cafés', 1, '2026-09-26 18:24:53'),
(2, 'Cappuccino Cremoso', 'Espresso, leite e espuma.', 12.00, 'img/2.jpg', '☕', 98, 'Cafés', 1, '2026-09-26 18:24:53'),
(3, 'Croissant Artesanal', 'Massa leve e amanteigada.', 11.00, 'img/4.jpg', '🥐', 100, 'Salgados', 1, '2026-09-26 18:24:53'),
(4, 'Brownie de Chocolate', 'Brownie com cobertura crocante.', 11.00, 'img/6.jpg', '🍫', 97, 'Doces', 1, '2026-09-26 18:24:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `role`, `criado_em`) VALUES
(1, 'lucas', 'lucas@gmail.com', '$2y$10$H.q4c2TpkYfzGf20KT43PeM6rP6wbtMy.QpdelVe6N8wDAbnJhG7u', 'admin', '2026-09-26 18:26:43'),
(2, 'caila', 'caila@gmail.com', '$2y$10$G/5QjGQvEwSqgSU./c860.660DYf4cfQ0VYoSZ6dhXhcGkX/NK5FW', 'user', '2026-09-26 18:35:45'),
(3, 'lens', 'lens@gmail.com', '$2y$10$kDhA6T3JzLwADpbLiqmyD.GgiN1g2O1p7hZh7ekuMPH/r2lIHcbHS', 'user', '2026-09-26 18:38:11'),
(4, 'kenny', 'kenny@gmail.com', '$2y$10$A93iuws/VEZREQsx70B/kOINdfN5zV8j1HBAMjTAlQFi.WQ60/j.S', 'user', '2026-09-26 19:30:14');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_itens_pedido` (`pedido_id`),
  ADD KEY `idx_itens_produto` (`produto_id`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mensagens_lidas`
--
ALTER TABLE `mensagens_lidas`
  ADD PRIMARY KEY (`mensagem_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedidos_usuario` (`usuario_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `fk_itens_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_itens_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
