-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07-Abr-2016 às 21:19
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
-- Estrutura da tabela `z_produto_foto`
--

CREATE TABLE IF NOT EXISTS `z_produto_foto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `z_produto_id` int(11) NOT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileext` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Para SEO',
  PRIMARY KEY (`id`),
  KEY `z_produto_id` (`z_produto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `z_produto_foto`
--

INSERT INTO `z_produto_foto` (`id`, `z_produto_id`, `filename`, `fileext`, `titulo`, `alt`) VALUES
(3, 2, 'f493a16b82a5853111a664f79b4c657e', 'jpg', NULL, ''),
(4, 2, 'f493a16b82a5853111a664f79b4c657e_400_300', 'jpg', NULL, ''),
(5, 2, 'dc916e6e84b06b4439308c731f75d372', 'png', NULL, ''),
(6, 2, 'dc916e6e84b06b4439308c731f75d372_400_300', 'png', NULL, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
