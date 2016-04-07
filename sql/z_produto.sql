-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07-Abr-2016 às 21:17
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `guiafacil`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `z_produto`
--

CREATE TABLE IF NOT EXISTS `z_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `desconto_porcento` decimal(3,2) DEFAULT NULL,
  `novo_preco` decimal(10,2) DEFAULT NULL,
  `detalhe` text COLLATE utf8mb4_unicode_ci,
  `cliente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `z_produto`
--

INSERT INTO `z_produto` (`id`, `nome`, `preco`, `desconto_porcento`, `novo_preco`, `detalhe`, `cliente_id`) VALUES
(2, 'Dorflex', '4.99', NULL, NULL, 'Remédio para dor de cabeça', 2);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `z_produto`
--
ALTER TABLE `z_produto`
  ADD CONSTRAINT `z_produto_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
