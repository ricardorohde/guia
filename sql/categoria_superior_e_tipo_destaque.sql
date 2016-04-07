ALTER TABLE `grupo` ADD `id_grupo_superior` INT NULL DEFAULT NULL COMMENT 'Grupo superior, referência para a própria tabela.' , ADD INDEX (`id_grupo_superior`) ;
ALTER TABLE `grupo` ADD FOREIGN KEY (`id_grupo_superior`) REFERENCES `guiafacil`.`grupo`(`grupo_id`) ON DELETE SET NULL ON UPDATE RESTRICT;

INSERT INTO `guiafacil`.`grupo` (`grupo_id`, `grupo_nome`, `grupo_url`, `grupo_pos`, `grupo_icone`, `id_grupo_superior`) VALUES (NULL, 'Gastronomia', 'gastronomia', '999', 'marker.png', NULL);

-- INICIO - TABELA QUE GUARDA O TIPO DE DESTAQUE. EXEMPLO: DESTAQUE NO MAPA, NO RODAPÉ, NO RODAPÉ DO PERFIL DE ALGUMA CATEGORIA, ETC
CREATE TABLE IF NOT EXISTS `tipo_destaque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
-- FIM - TABELA QUE GUARDA O TIPO DE DESTAQUE. EXEMPLO: DESTAQUE NO MAPA, NO RODAPÉ, NO RODAPÉ DO PERFIL DE ALGUMA CATEGORIA, ETC

-- INICIO - RELAÇÃO ENTRE OS DESTAQUES E AS CATEGORIAS. EXEMPLO: A CATEGORIA GASTRONOMIA PODE TER DESTAQUE NO RODAPÉ E NO MAPA
CREATE TABLE IF NOT EXISTS `grupo_tipo_destaque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_id` int(11) NOT NULL,
  `tipo_destaque_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grupo_id` (`grupo_id`,`tipo_destaque_id`),
  KEY `tipo_destaque_id` (`tipo_destaque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


ALTER TABLE `grupo_tipo_destaque`
  ADD CONSTRAINT `grupo_tipo_destaque_ibfk_2` FOREIGN KEY (`tipo_destaque_id`) REFERENCES `tipo_destaque` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grupo_tipo_destaque_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`grupo_id`) ON DELETE CASCADE;
-- FIM - RELAÇÃO ENTRE OS DESTAQUES E AS CATEGORIAS. EXEMPLO: A CATEGORIA GASTRONOMIA PODE TER DESTAQUE NO RODAPÉ E NO MAPA
  

-- INICIO - RELAÇÃO ENTRE OS DESTAQUES E OS CLIENTES. EXEMPLO: OS CLIENTES 10, 12, 18 E 25 TEM DESTAQUE NO MAPA. JÁ OS 33, 44, 19 E 20 TEM DESTAQUE NO FOOTER.
CREATE TABLE IF NOT EXISTS `cliente_tipo_destaque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo_destaque_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`,`tipo_destaque_id`),
  KEY `tipo_destaque_id` (`tipo_destaque_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

ALTER TABLE `cliente_tipo_destaque`
  ADD CONSTRAINT `cliente_tipo_destaque_ibfk_2` FOREIGN KEY (`tipo_destaque_id`) REFERENCES `tipo_destaque` (`id`),
  ADD CONSTRAINT `cliente_tipo_destaque_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`);
-- FIM - RELAÇÃO ENTRE OS DESTAQUES E OS CLIENTES. EXEMPLO: OS CLIENTES 10, 12, 18 E 25 TEM DESTAQUE NO MAPA. JÁ OS 33, 44, 19 E 20 TEM DESTAQUE NO FOOTER.

-- INTEGRIDADE ENTRE GRUPO E CLIENTE
ALTER TABLE `cliente` ADD INDEX(`cliente_grupo`);
-- INTEGRIDADE ENTRE GRUPO E CLIENTE