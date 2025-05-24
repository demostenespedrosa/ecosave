-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/05/2025 às 23:27
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
-- Banco de dados: `ecosave`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `businesses`
--

CREATE TABLE `businesses` (
  `business_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `business_name` varchar(200) NOT NULL,
  `address` varchar(255) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `avg_rating` decimal(2,1) DEFAULT 0.0 COMMENT 'Avaliação média de 0.0 a 5.0',
  `rating_count` int(11) DEFAULT 0 COMMENT 'Número total de avaliações'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `businesses`
--

INSERT INTO `businesses` (`business_id`, `user_id`, `category_id`, `business_name`, `address`, `number`, `complement`, `neighborhood`, `city`, `state`, `cep`, `description`, `phone`, `latitude`, `longitude`, `avg_rating`, `rating_count`) VALUES
(1, 2, NULL, 'Padaria Paraíso', 'Rua do teste', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.4, 13);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Padarias', 'Pães, bolos, salgados e delícias de padaria.'),
(2, 'Restaurantes', 'Refeições completas, pratos variados e mais.'),
(3, 'Supermercados', 'Produtos próximos ao vencimento ou excedentes.'),
(4, 'Hortifrutis', 'Frutas, verduras e legumes frescos.'),
(5, 'Lanchonetes', 'Salgados, sanduíches, sucos e lanches rápidos.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity_available` int(11) NOT NULL,
  `pickup_start_time` datetime NOT NULL,
  `pickup_end_time` datetime NOT NULL,
  `status` enum('active','inactive','sold_out') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `offers`
--

INSERT INTO `offers` (`offer_id`, `business_id`, `category_id`, `title`, `description`, `image_path`, `price`, `quantity_available`, `pickup_start_time`, `pickup_end_time`, `status`, `created_at`) VALUES
(3, 1, 1, 'Sacola Mista', 'Sacola com paes.', 'uploads/offer_1748042797_bbf0561da8f9f405.png', 20.00, 4, '2025-05-24 01:00:00', '2025-05-24 03:00:00', 'inactive', '2025-05-23 23:26:37'),
(4, 1, 2, 'Marmita P', 'Marmita com 3 guarniçoes e 1 proteina', 'uploads/offer_1748043923_09f22173ece2f176.png', 10.00, 2, '2025-05-23 21:00:00', '2025-05-23 23:00:00', 'inactive', '2025-05-23 23:45:23'),
(5, 1, 1, 'Sacola Pães', 'Sacola só de pães', 'uploads/offer_1748044157_ada407ada5869f85.png', 5.00, 6, '2025-05-23 21:00:00', '2025-05-23 23:00:00', 'inactive', '2025-05-23 23:49:17'),
(6, 1, 2, 'Marmita M', '4 guarnições e 2 proteinas', 'uploads/offer_1748044246_a5891a055091d6f4.png', 12.00, 3, '2025-05-23 21:00:00', '2025-05-23 22:00:00', 'inactive', '2025-05-23 23:50:46'),
(7, 1, 1, 'Teste', 'Sacola teste ', 'uploads/offer_1748119186_1961575ee3fc332d.png', 5.00, 3, '2025-05-24 18:00:00', '2025-05-24 20:00:00', 'active', '2025-05-24 20:39:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `quantity_reserved` int(11) NOT NULL DEFAULT 1,
  `reservation_code` varchar(10) NOT NULL,
  `status` enum('reserved','collected','cancelled') NOT NULL DEFAULT 'reserved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `offer_id`, `quantity_reserved`, `reservation_code`, `status`, `created_at`) VALUES
(1, 3, 7, 1, 'A1849D', 'collected', '2025-05-24 21:12:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(150) NOT NULL,
  `user_type` enum('consumer','business') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`user_id`, `email`, `password_hash`, `name`, `user_type`, `created_at`, `address`, `number`, `complement`, `neighborhood`, `city`, `state`, `cep`, `latitude`, `longitude`) VALUES
(2, 'analystecnologias@gmail.com', '$2y$10$sOoMeNjXQQPtJ.69ezkvouTmSev0RvB3S2IQd4BhUDR87Zo.WI4hW', 'Demóstenes', 'business', '2025-05-23 19:11:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'demostenestj@gmail.com', '$2y$10$wWWyee1CJgZYWJm1ddzFGOOMwRQ8lRFSfgagBTAQldq5vUMrqf.OO', 'Demóstenes', 'consumer', '2025-05-23 22:08:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`business_id`),
  ADD KEY `user_id_fk` (`user_id`),
  ADD KEY `businesses_ibfk_2` (`category_id`);

--
-- Índices de tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `business_id_fk` (`business_id`),
  ADD KEY `offers_ibfk_2` (`category_id`);

--
-- Índices de tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `reservation_code` (`reservation_code`),
  ADD KEY `user_id_order_fk` (`user_id`),
  ADD KEY `offer_id_order_fk` (`offer_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `businesses`
--
ALTER TABLE `businesses`
  MODIFY `business_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `businesses`
--
ALTER TABLE `businesses`
  ADD CONSTRAINT `businesses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `businesses_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
