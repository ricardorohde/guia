
CREATE TABLE IF NOT EXISTS `acesso` (
  `acesso_id` int(11) NOT NULL AUTO_INCREMENT,
  `acesso_data` varchar(200) DEFAULT NULL,
  `acesso_ip` varchar(200) DEFAULT NULL,
  `acesso_url` varchar(500) DEFAULT NULL,
  `acesso_agent` varchar(200) DEFAULT NULL,
  `acesso_hora` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`acesso_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=403 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `area`
--

CREATE TABLE IF NOT EXISTS `area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_nome` varchar(200) DEFAULT NULL,
  `area_url` varchar(200) DEFAULT NULL,
  `area_pos` int(3) DEFAULT '0',
  `area_show` int(11) DEFAULT '1',
  `area_footer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `area`
--

INSERT INTO `area` (`area_id`, `area_nome`, `area_url`, `area_pos`, `area_show`, `area_footer`) VALUES
(1, 'ServiÃ§os', 'servicos', 1, 1, 1),
(2, 'Empresa', 'empresa', 0, 0, 1),
(10, 'DÃºvidas', 'duvidas', 2, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_nome` varchar(200) DEFAULT NULL,
  `categoria_pos` int(11) DEFAULT '0',
  `categoria_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`categoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria_nome`, `categoria_pos`, `categoria_url`) VALUES
(1, 'Insitucional', 1, 'insitucional'),
(2, 'Lojas', 0, 'lojas'),
(3, 'ImobiliÃ¡ria', 0, 'imobiliaria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `cliente_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_nome` varchar(200) DEFAULT NULL,
  `cliente_fone` varchar(20) DEFAULT NULL,
  `cliente_fone1` varchar(20) DEFAULT NULL,
  `cliente_fone2` varchar(20) DEFAULT NULL,
  `cliente_fone3` varchar(20) DEFAULT NULL,
  `cliente_fone4` varchar(20) DEFAULT NULL,
  `cliente_email` varchar(200) DEFAULT NULL,
  `cliente_empresa` varchar(200) DEFAULT NULL,
  `cliente_logo` varchar(200) DEFAULT NULL,
  `cliente_texto` text,
  `cliente_depoimento` text,
  `cliente_foto` varchar(200) DEFAULT NULL,
  `cliente_site` varchar(200) DEFAULT NULL,
  `cliente_meta_keywords` varchar(200) DEFAULT NULL,
  `cliente_meta_desc` varchar(200) DEFAULT NULL,
  `cliente_click_view` int(11) DEFAULT '0',
  `cliente_click_uniq` int(11) DEFAULT '0',
  `cliente_grupo` int(5) DEFAULT NULL,
  `cliente_subgrupo` int(11) DEFAULT NULL,
  `cliente_info` longtext,
  `cliente_url` varchar(200) DEFAULT NULL,
  `cliente_cep` varchar(200) DEFAULT NULL,
  `cliente_endereco` varchar(200) DEFAULT NULL,
  `cliente_num` varchar(200) DEFAULT NULL,
  `cliente_complemento` varchar(200) DEFAULT NULL,
  `cliente_bairro` varchar(200) DEFAULT NULL,
  `cliente_cidade` varchar(200) DEFAULT NULL,
  `cliente_uf` varchar(200) DEFAULT NULL,
  `cliente_fb` varchar(200) DEFAULT NULL,
  `cliente_tw` varchar(200) DEFAULT NULL,
  `cliente_lk` varchar(200) DEFAULT NULL,
  `cliente_in` varchar(200) DEFAULT NULL,
  `cliente_gp` varchar(200) DEFAULT NULL,
  `cliente_sk` varchar(200) DEFAULT NULL,
  `cliente_lat_lon` varchar(200) DEFAULT NULL,
  `cliente_status` int(11) DEFAULT '1',
  `cliente_senha` varchar(200) DEFAULT NULL,
  `cliente_tipo` int(1) DEFAULT '1',
  `cliente_plano` int(5) DEFAULT '1',
  `cliente_datacad` datetime DEFAULT NULL,
  `cliente_dataexp` datetime DEFAULT NULL,
  `cliente_dataup` datetime DEFAULT NULL,
  PRIMARY KEY (`cliente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `cliente_nome`, `cliente_fone`, `cliente_fone1`, `cliente_fone2`, `cliente_fone3`, `cliente_fone4`, `cliente_email`, `cliente_empresa`, `cliente_logo`, `cliente_texto`, `cliente_depoimento`, `cliente_foto`, `cliente_site`, `cliente_meta_keywords`, `cliente_meta_desc`, `cliente_click_view`, `cliente_click_uniq`, `cliente_grupo`, `cliente_subgrupo`, `cliente_info`, `cliente_url`, `cliente_cep`, `cliente_endereco`, `cliente_num`, `cliente_complemento`, `cliente_bairro`, `cliente_cidade`, `cliente_uf`, `cliente_fb`, `cliente_tw`, `cliente_lk`, `cliente_in`, `cliente_gp`, `cliente_sk`, `cliente_lat_lon`, `cliente_status`, `cliente_senha`, `cliente_tipo`, `cliente_plano`, `cliente_datacad`, `cliente_dataexp`, `cliente_dataup`) VALUES
(2, 'FarmÃ¡cia e perfumaria', '(84) 3271-3085', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Drogaria Guedes - Matriz', 'ad020bc3ce474f6b56d368ce356618d5.jpg', NULL, NULL, NULL, '', 'farmÃ¡cia, farmacia, drogaria, remÃ©dio, medicamentos', NULL, 54, 10, 19, NULL, 'Na Drogaria Guedes vocÃª encontra tambÃ©m a FarmÃ¡cia Popular, com medicamentos grÃ¡tis para hipertensÃ£o, diabetes e asma. Venha conhecer a nossa loja com ambiente climatizado, bom atendimento e excelentes preÃ§os.\r\nDisk Entrega: (84) 3271-3085', 'drogaria-guedes-matriz-farmacia-e-perfumaria', '08615-000', 'Rua BiotÃ´nico', '238', '', 'Vila UrupÃªs', 'Suzano', 'SP', 'https://facebook.com.br/', 'https://twitter.com', 'https://linkedin.com', 'https://instagram', 'https://plus.google.com', 'empresa123', '{"lat":"-23.5557581","lon":"-46.2914673"}', 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(3, 'A melhor pizza da cidade', '(11) 5555-4444', NULL, '(11) 9888-55555', NULL, NULL, 'contato@rds.com.br', 'Dema Joe Pizzas', '70df391d560354fc76621a345b9923c6.jpg', NULL, NULL, NULL, 'http://google.com.br', 'pizza, esfihas', NULL, 62, 13, 1, NULL, 'value="value="Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.""', 'dema-joe-pizzas-a-melhor-pizza-da-cidade', '11701-090', 'Av. Costa e Silva', '100', '', 'Vila Guilhermina', 'Praia Grande', 'SP', 'https://facebook.com.br/', 'https://twitter.com', 'https://linkedin.com', 'https://instagram', 'https://plus.google.com', 'empresa123', '{"lat":"-5.3040151","lon":"-44.4962575"}', 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(4, 'Rafael', '(11) 5555-44444', NULL, '', NULL, NULL, '', 'Fulano Steps', 'b0dca97e27c7179bef451137da74ac94.jpg', NULL, NULL, NULL, '', '', NULL, 5, 2, 2, NULL, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'fulano-steps-rafael', '11701-000', 'Avenida Presidente Costa e Silva', '', '', 'BoqueirÃ£o', 'Praia Grande', 'SP', 'https://facebook.com.br/', 'https://twitter.com', 'https://linkedin.com', 'https://instagram', 'https://plus.google.com', 'empresa123', '{"lat":"-3.1289411","lon":"-59.9980276"}', 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(5, 'Joe Dam', '', NULL, '', NULL, NULL, 'wp2@wp.com', 'Wordpress', '86ad6c8be22949ec89283338dc847b28.jpg', NULL, NULL, NULL, '', '', NULL, 5, 2, 6, NULL, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'wordpress-joe-dam', '11701-090', 'Av. Brasil - 900', '', '', '', 'Santos', 'SP', 'https://facebook.com.br/', 'https://twitter.com', 'https://linkedin.com', 'https://instagram', 'https://plus.google.com', 'empresa123', '{"lat":"-23.2820213","lon":"-45.8328368"}', 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(6, 'RAPAZ', '', NULL, '', NULL, NULL, 'teste@teste.com', 'Os melhores preÃ§os do Brasil', 'd80d0c79931dd1aa9f9ac2cf2bf82b15.jpg', NULL, NULL, NULL, '', '', NULL, 4, 2, 3, NULL, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'rapaz', '11701-090', 'Av. Brasil - 900', NULL, NULL, NULL, 'Santos', 'SP', 'https://facebook.com.br/', 'https://twitter.com', 'https://linkedin.com', 'https://instagram', 'https://plus.google.com', 'empresa123', NULL, 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(9, 'O melhor Chopp da regiÃ£o', '(11) 5555-88888', NULL, '(11) 9999-98888', NULL, NULL, 'sac@brasachopp.com.br', 'Brasa Chopp', '012ced3a29c936b24617eb10adeb67ce.jpg', NULL, NULL, NULL, 'http://brasachopp.com.br', 'buffet em praia grande', NULL, 51, 8, 2, NULL, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'brasa-chopp-o-melhor-chopp-da-regiao', '11701-090', 'Avenida Brasil, 900, Praia Grande - SP', '', '', '', 'Santos', 'SP', 'https://facebook.com.br/', 'https://twitter.com', 'https://linkedin.com', 'https://instagram', 'https://plus.google.com', 'empresa123', '{"lat":"-24.0117586","lon":"-46.4206907"}', 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(11, 'O melhor som de SP', '(11) 5555-5555', NULL, '', NULL, NULL, 'rafadinix@gmail.com', 'Rock Bar 88', 'b11cc08455b74cf8e0e87a32816fa91c.jpg', NULL, NULL, NULL, 'http://phpstaff.com.br', 'rock,bar, cerveja,noite,barzinho,chopp,mÃºsica ao vivo', NULL, 1, 1, 17, NULL, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.', 'rock-bar-88-o-melhor-som-de-sp', '11701-090', 'Avenida Brasil', '100', '', 'BoqueirÃ£o', 'Praia Grande', 'SP', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-22.8612317","lon":"-43.4521875"}', 2, '202cb962ac59075b964b07152d234b70', 1, 1, NULL, NULL, NULL),
(12, 'Drogaria Menor PreÃ§o', '(84) 3271-1065', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Drogaria Menor PreÃ§o', NULL, NULL, NULL, NULL, '', 'farmÃ¡cia, drogaria, farmÃ¡cia em macaiba, drogaria em macaiba, remÃ©dio', NULL, 0, 0, 3, NULL, 'Qualidade em atendimento.', 'drogaria-menor-preco-drogaria-menor-preco', '59280000', 'Rua Dr. Pedro Velho', '202', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-6.311369","lon":"-35.4786518"}', 1, NULL, 1, 1, NULL, NULL, NULL),
(13, '', '(84) 3271-3469', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Infoserv - InformÃ¡tica e papelaria', '87302005e47806d84f54a3c5714b941b.jpg', NULL, NULL, NULL, 'http://infoservrn.com.br', 'informÃ¡tica, computador, acessÃ³rios informÃ¡tica, informÃ¡tica em macaiba', NULL, 3, 3, 4, NULL, 'PreÃ§o e qualidade Ã© com a Infoserv.', 'infoserv-informatica-e-papelaria', '59280000', 'Rua Nossa Senhora da ConceiÃ§Ã£o', '154', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"37.8220669","lon":"-25.5201333"}', 2, NULL, 2, 1, NULL, NULL, NULL),
(14, '', '(84) 9412-5215', NULL, '', NULL, NULL, 'evandro@macaibaonline.com', 'Marcos AurÃ©lio Fotografia', 'a095969ab3c68291ba5519e60c2ee3f7.jpg', NULL, NULL, NULL, '', 'fotografia, fotos, vÃ­deo, fotogrÃ¡fo em macaiba', NULL, 3, 2, 20, NULL, 'Fotos e vÃ­deos.', 'marcos-aurelio-fotografia', '59280-000', 'MacaÃ­ba', 'Rua Nair Mesquita, 39', '', 'Centro', 'MacaÃ­ba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-5.8529659","lon":"-35.3530163"}', 2, '123456', 1, 1, NULL, NULL, NULL),
(15, 'Academia NFitness', '(84) 9192-9266', NULL, '(84) 9190-4715', NULL, NULL, 'teste@demo.com', 'Academia NFitness', NULL, NULL, NULL, NULL, '', 'academia, sporte', NULL, 0, 0, 14, NULL, 'Treinamentos diferenciados, resultados garantidos e Ã³tima localizaÃ§Ã£o.\r\nTemos pacotes promocionais!\r\nEm frente ao BoticÃ¡rio.', 'academia-nfitness-academia-nfitness', '59280-000', 'Rua Nossa Senhora da ConceiÃ§Ã£o', '', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"37.8220669","lon":"-25.5201333"}', 2, '9a286406c252a3d14218228974e1f567', 1, 1, NULL, NULL, NULL),
(16, 'FarmÃ¡cia e perfumaria', '(84) 3271-2682', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Drogaria Guedes - Filial', 'a00293b0a3fe79e3797276b9a95f4c57.jpg', NULL, NULL, NULL, '', 'farmÃ¡cia, drogaria, farmÃ¡cia em macaiba, drogaria em macaiba, remÃ©dio', NULL, 0, 0, 3, NULL, 'Na Drogaria Guedes vocÃª encontra tambÃ©m a FarmÃ¡cia Popular, com medicamentos grÃ¡tis para hipertensÃ£o, diabetes e asma. Venha conhecer a nossa loja com ambiente climatizado, bom atendimento e excelentes preÃ§os.\r\nDisk Entrega: (84) 3271-3085', 'drogaria-guedes-filial-farmacia-e-perfumaria', '59280000', 'Rua Dr. Pedro Velho', '86', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-6.311369","lon":"-35.4786518"}', 2, NULL, 1, 1, NULL, NULL, NULL),
(21, 'Drogaria Macedo', '(84) 3271-2020', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Drogaria Macedo', NULL, NULL, NULL, NULL, '', 'farmÃ¡cia, drogaria, farmÃ¡cia em macaiba, drogaria em macaiba, remÃ©dio', NULL, 0, 0, 3, NULL, 'O melhor atendimento.', 'drogaria-macedo-drogaria-macedo', '59280000', 'Rua Dr. Pedro Velho', '142', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-6.311369","lon":"-35.4786518"}', 2, NULL, 1, 1, NULL, NULL, NULL),
(22, 'Texas InformÃ¡tica', '(84) 3611-0008', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Texas InformÃ¡tica', '7ab79360a29be8074eb6d2c509eafe29.jpg', NULL, NULL, NULL, '', '', NULL, 0, 0, 4, NULL, 'InformÃ¡tica Equipamento Fab e Venda.', 'texas-informatica-texas-informatica', '59056105', 'Av. Romualdo GalvÃ£o', '952', '', 'Lagoa Nova', 'Natal', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-5.8126654","lon":"-35.2084191"}', 2, NULL, 1, 1, NULL, NULL, NULL),
(23, '', '(84) 3271-1515', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Panificadora Gama', NULL, NULL, NULL, NULL, '', 'pÃ£o, pÃ£es, padaria em macaiba, panificadora em macaiba', NULL, 1, 1, 21, NULL, 'PÃ£es e massas.', 'panificadora-gama', '59280000', 'Rua Doutor Francisco da Cruz', '197', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-5.8572953","lon":"-35.3541727"}', 2, NULL, 1, 1, NULL, NULL, NULL),
(24, '', '(84) 3271-1227', NULL, '', NULL, NULL, 'marketing@macaibaonline.com', 'Panificadora Fibra PÃ£o', NULL, NULL, NULL, NULL, '', 'pÃ£o, pÃ£es, padaria em macaiba, panificadora em macaiba', NULL, 0, 0, 21, NULL, 'PÃ£es e massas.', 'panificadora-fibra-pao', '59280000', 'Rua JoÃ£o Pessoa', '56', '', 'Centro', 'Macaiba', 'RN', NULL, NULL, NULL, NULL, NULL, NULL, '{"lat":"-20.6409861","lon":"-46.506245"}', 2, NULL, 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentario`
--

CREATE TABLE IF NOT EXISTS `comentario` (
  `comentario_id` int(11) NOT NULL AUTO_INCREMENT,
  `comentario_pagina` int(11) DEFAULT NULL,
  `comentario_data` varchar(200) DEFAULT NULL,
  `comentario_assunto` varchar(200) DEFAULT NULL,
  `comentario_mensagem` text,
  `comentario_nome` varchar(200) DEFAULT NULL,
  `comentario_email` varchar(200) DEFAULT NULL,
  `comentario_status` int(11) DEFAULT '0',
  PRIMARY KEY (`comentario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contato`
--

CREATE TABLE IF NOT EXISTS `contato` (
  `contato_id` int(11) NOT NULL AUTO_INCREMENT,
  `contato_fone` varchar(20) DEFAULT NULL,
  `contato_fone1` varchar(200) DEFAULT NULL,
  `contato_fone2` varchar(200) DEFAULT NULL,
  `contato_fone3` varchar(200) DEFAULT NULL,
  `contato_fone4` varchar(200) DEFAULT NULL,
  `contato_fone5` varchar(200) DEFAULT NULL,
  `contato_email` varchar(200) DEFAULT NULL,
  `contato_endereco` varchar(200) DEFAULT NULL,
  `contato_complemento` varchar(200) DEFAULT NULL,
  `contato_bairro` varchar(200) DEFAULT NULL,
  `contato_cidade` varchar(200) DEFAULT NULL,
  `contato_uf` varchar(2) DEFAULT NULL,
  `contato_cep` varchar(20) DEFAULT NULL,
  `contato_lat` varchar(200) DEFAULT NULL,
  `contato_lon` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`contato_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `contato`
--

INSERT INTO `contato` (`contato_id`, `contato_fone`, `contato_fone1`, `contato_fone2`, `contato_fone3`, `contato_fone4`, `contato_fone5`, `contato_email`, `contato_endereco`, `contato_complemento`, `contato_bairro`, `contato_cidade`, `contato_uf`, `contato_cep`, `contato_lat`, `contato_lon`) VALUES
(1, '(11) 4444-5555', '(11) 5555-4444', '(11) 999-888-777 (WhatsApp)', '(11) 555-444-333 (TIM)', '(11) 666-444-333 (VIVO)', '(11) 888-444-333 (CLARO)', 'get@in.touch', 'Avenida Brasil, Nº 500', 'Sala 101', 'Boqueirão', 'Praia Grande', 'SP', '11701-090', '-24.0105041', '-46.4174317');

-- --------------------------------------------------------

--
-- Estrutura da tabela `depoimento`
--

CREATE TABLE IF NOT EXISTS `depoimento` (
  `depoimento_id` int(11) NOT NULL AUTO_INCREMENT,
  `depoimento_autor` varchar(200) DEFAULT NULL,
  `depoimento_cliente` int(11) DEFAULT NULL,
  `depoimento_texto` varchar(2000) DEFAULT NULL,
  `depoimento_data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `depoimento_status` int(11) NOT NULL DEFAULT '0',
  `depoimento_cargo` varchar(200) DEFAULT NULL,
  `depoimento_foto` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`depoimento_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `depoimento`
--

INSERT INTO `depoimento` (`depoimento_id`, `depoimento_autor`, `depoimento_cliente`, `depoimento_texto`, `depoimento_data`, `depoimento_status`, `depoimento_cargo`, `depoimento_foto`) VALUES
(1, 'Rafael Clares', 2, 'Estamos satisfeitos com a solucao oxygen cms. Nosso site ficou mais dinamico e moderno, alÃ©m de facilidade na atualizacao de conteudo.', '2014-10-20 18:00:00', 1, 'Gerente de Vendas', NULL),
(2, 'Osvaldo Reis', 5, 'Apos iniciarmos na plataforma fluxshop ja notamos a diferenca em relacao as plataformas atuais do mercado que sao super complicadas. A fluxshopp e muito simples de usar, em poucos cliques temos nossos produtos a venda na internet e com todos os meios de pagamentos. Estamos adorando a plataforma. \r\nObrigado Ã  todos', '2014-10-28 17:00:00', 1, 'Depto Comercial', NULL),
(3, 'JoÃ£o Pedro', 3, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor inviduntLorem ipsum d', '2014-10-29 08:07:25', 1, 'Supervisor Geral', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_pergunta` longtext,
  `faq_resposta` longtext,
  `faq_topico` int(11) DEFAULT NULL,
  `faq_footer` int(1) DEFAULT '1',
  `faq_show` int(1) DEFAULT '1',
  `faq_pos` int(1) DEFAULT '99',
  `faq_url` varchar(800) DEFAULT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `faq`
--

INSERT INTO `faq` (`faq_id`, `faq_pergunta`, `faq_resposta`, `faq_topico`, `faq_footer`, `faq_show`, `faq_pos`, `faq_url`) VALUES
(1, 'Pergunta 01', '<p>Resposta 01</p>', 1, 1, 1, 0, 'pergunta-01'),
(2, 'Pergunta 02', '<p>Resposta 02</p>', NULL, 1, 1, 1, 'pergunta-02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `foto`
--

CREATE TABLE IF NOT EXISTS `foto` (
  `foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `foto_nome` varchar(200) DEFAULT NULL,
  `foto_pos` int(11) DEFAULT '0',
  `foto_url` varchar(200) DEFAULT NULL,
  `foto_galeria` int(11) DEFAULT NULL,
  PRIMARY KEY (`foto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

--
-- Extraindo dados da tabela `foto`
--

INSERT INTO `foto` (`foto_id`, `foto_nome`, `foto_pos`, `foto_url`, `foto_galeria`) VALUES
(12, 'QÃºen', NULL, '6e9569ae595e4842c91be0e4a95882a8.jpg', 1),
(13, 'E Macarenaaaaa', NULL, 'a9ddce88c2b91aebd238bd354adeb6f9.jpg', 1),
(14, 'QÃºen', NULL, '6b4248ea8b0521beabcd35a6fe95515d.jpg', 1),
(15, 'macacada reunida 33', NULL, '10888cb6a8273eaea5e80e3b542f5021.jpg', 1),
(16, 'macacada reunida 33', NULL, '5b237c76523c8dece43aaffe58ccd9cb.jpg', 1),
(17, 'macacada reunida', NULL, 'ee0a23be1c98baa5e5a5e80771787666.jpg', 1),
(18, 'E Macarenaaaaa', NULL, '129a7b28e3518bc44654ef2f93eba1fa.jpg', 1),
(19, 'E Macarenaaaaa', NULL, 'd100e4be107c782e07fe6fe5fb4c7226.jpg', 1),
(50, 'Girl Another', 3, '551697665938d12303c950cc89bc9198.jpg', 2),
(51, 'teste', 0, 'f11067858627b459f533ce7f435bf8b5.jpg', 2),
(56, 'PHP Lefante', 1, 'c4229bd6c17623357e266f298dfb430a.jpg', 2),
(57, 'rapaz', 5, 'a539e9555e8cdd3044483eac14810104.jpg', 2),
(58, 'fimaria', 2, '1a2dbb6031e8294c3dd4c277acb1ee94.jpg', 2),
(59, 'mulazebrada', 4, '34e7988397fc4875028eeba1fe0b05f6.jpg', 2),
(60, 'joe', NULL, 'ac8d981297720c9ecd721159c4df7424.jpg', 3),
(61, 'foo', NULL, '41e56057cac4aa28beb807437d0a70a4.jpg', 3),
(62, 'bar', NULL, '3600d025e53610d7f433274846c03e28.jpg', 3),
(63, 'baz', NULL, '9fd0c3ad5a2290c22596f403f5c242d1.jpg', 3),
(64, NULL, NULL, 'c31e702a0c75d1e9c13d03b46f1f335d.jpg', 3),
(65, '', NULL, '923c05c8f89142fb76bf677a1f08654c.jpg', 3),
(71, 'foto 01', 1, 'afd587c2085b38e21df7c45e3d4b6a34.jpg', 4),
(72, NULL, 4, '9ac286e36a87ea776604ed474f73e8e2.jpg', 5),
(73, NULL, 3, '094449567eaf6cc5f6979163ac2d25b1.jpg', 5),
(74, NULL, 2, '22579693a40f65f132ec434380a376f4.jpg', 5),
(77, 'Legenda da foto', 0, 'ab754f590391cc47d49fcfedd626e22e.jpg', 4),
(79, 'outra qualquer', 3, 'a3604768b7edbd2e29292035dd1c0668.jpg', 4),
(83, NULL, 0, '25d37273d95d747e56bde0f66b9ad7a1.jpg', 5),
(84, NULL, 1, 'a5a7cea2e68beff43b870fa7b50c74a8.jpg', 5),
(85, NULL, 0, '59fdc9eef84520eb16f66d5d564703e1.jpg', 6),
(86, 'teste', 2, '2457ad5945296e924b64201a812e7b73.jpg', 4),
(87, NULL, 0, 'f98d275b9b6cb143ec5ef943575fefab.jpg', 4),
(89, NULL, 0, '3bf648445083709db7a8f59301ee44e0.jpg', 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `galeria`
--

CREATE TABLE IF NOT EXISTS `galeria` (
  `galeria_id` int(11) NOT NULL AUTO_INCREMENT,
  `galeria_nome` varchar(200) DEFAULT NULL,
  `galeria_pos` int(11) DEFAULT '0',
  `galeria_url` varchar(200) DEFAULT NULL,
  `galeria_show` int(11) NOT NULL DEFAULT '1',
  `galeria_desc` longtext,
  `galeria_gcategoria` int(11) DEFAULT NULL,
  `galeria_meta_keywords` text,
  `galeria_meta_desc` text,
  `galeria_click_view` int(11) NOT NULL DEFAULT '0',
  `galeria_click_uniq` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`galeria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `galeria`
--

INSERT INTO `galeria` (`galeria_id`, `galeria_nome`, `galeria_pos`, `galeria_url`, `galeria_show`, `galeria_desc`, `galeria_gcategoria`, `galeria_meta_keywords`, `galeria_meta_desc`, `galeria_click_view`, `galeria_click_uniq`) VALUES
(4, 'Projeto 01', 0, 'projeto-01', 1, '<div style="text-align: justify;"><span style="line-height: 1.42857143;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span></div>', 3, NULL, NULL, 0, 0),
(5, 'Projeto 02', 1, 'projeto-02', 1, '<span style="font-weight: bold;">Cliente:</span> Asus<br><span style="font-weight: bold;">Tipo:</span> WebSite<br><span style="font-weight: bold;">DuraÃ§Ã£o: </span>30 Dias<br><span style="font-weight: bold;">Visitar o site:</span> www.asus.com.br', 1, NULL, NULL, 0, 0),
(6, 'Projeto 03', 2, 'projeto-03', 1, '<span style="font-weight: bold;">Cliente: </span>Flux<br><span style="font-weight: bold;">\r\nTipo:</span> WebServices<br><span style="font-weight: bold;">\r\nDuraÃ§Ã£o:</span> 15 Dias<br><span style="font-weight: bold;">\r\nVisitar o site:</span> www.fluxshop.com.br', 2, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gcategoria`
--

CREATE TABLE IF NOT EXISTS `gcategoria` (
  `gcategoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `gcategoria_nome` varchar(200) DEFAULT NULL,
  `gcategoria_url` varchar(200) DEFAULT NULL,
  `gcategoria_pos` int(11) DEFAULT '0',
  PRIMARY KEY (`gcategoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `gcategoria`
--

INSERT INTO `gcategoria` (`gcategoria_id`, `gcategoria_nome`, `gcategoria_url`, `gcategoria_pos`) VALUES
(1, 'WebSites', 'websites', 0),
(2, 'Sistemas', 'sistemas', 0),
(3, 'E-commerce', 'e-commerce', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo`
--

CREATE TABLE IF NOT EXISTS `grupo` (
  `grupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_nome` varchar(200) DEFAULT NULL,
  `grupo_url` varchar(200) DEFAULT NULL,
  `grupo_pos` int(11) DEFAULT '999',
  PRIMARY KEY (`grupo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Extraindo dados da tabela `grupo`
--

INSERT INTO `grupo` (`grupo_id`, `grupo_nome`, `grupo_url`, `grupo_pos`) VALUES
(1, 'Geral', 'geral', 8),
(2, 'Buffet', 'buffet', 4),
(3, 'FarmÃ¡cias, Drogarias', 'farmacias-drogarias', 7),
(4, 'InformÃ¡tica - Artigos, Equipamentos e Suprimentos', 'informatica-artigos-equipamentos-e-suprimentos', 11),
(5, 'ImÃ³veis - Aluguel de Casa', 'imoveis-aluguel-de-casa', 10),
(6, 'Hospitais, Maternidades, Pronto Socorro', 'hospitais-maternidades-pronto-socorro', 9),
(7, 'Perfumaria', 'perfumaria', 13),
(8, 'EstÃ©tica, Beleza', 'estetica-beleza', 6),
(9, 'Beleza, SaÃºde', 'beleza-saude', 3),
(10, 'ServiÃ§os Gerais', 'servicos-gerais', 16),
(12, 'Telefonia', 'telefonia', 18),
(14, 'Academias', 'academias', 0),
(15, 'Sorveterias', 'sorveterias', 17),
(16, 'Lanchonetes', 'lanchonetes', 12),
(17, 'Bares', 'bares', 2),
(18, 'Restaurantes', 'restaurantes', 15),
(19, 'Pizzaria', 'pizzaria', 14),
(20, 'FotÃ³grafos', 'fotografos', 999),
(21, 'Padarias, Confeitarias', 'padarias-confeitarias', 999),
(22, 'ImÃ³veis - Aluguel de Apartamento', 'imoveis-aluguel-de-apartamento', 999),
(23, 'ImÃ³veis - Compra de Casa', 'imoveis-compra-de-casa', 999),
(24, 'ImÃ³veis - Compra de Apartamento', 'imoveis-compra-de-apartamento', 999),
(25, 'ImÃ³veis - Venda de Casa', 'imoveis-venda-de-casa', 999),
(26, 'ImÃ³veis - Venda de Apartamento', 'imoveis-venda-de-apartamento', 999),
(27, 'ImÃ³veis - Venda de Terreno', 'imoveis-venda-de-terreno', 999);

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulo`
--

CREATE TABLE IF NOT EXISTS `modulo` (
  `modulo_id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo_status` int(11) DEFAULT '0',
  `modulo_nome` varchar(200) DEFAULT NULL,
  `modulo_core` varchar(200) DEFAULT NULL,
  `modulo_ordem` int(11) NOT NULL DEFAULT '0',
  `modulo_config_options` text,
  `modulo_tpl_public` varchar(200) DEFAULT NULL,
  `modulo_tpl_admin` varchar(200) DEFAULT NULL,
  `modulo_dir` varchar(200) DEFAULT NULL,
  `modulo_menu_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`modulo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Extraindo dados da tabela `modulo`
--

INSERT INTO `modulo` (`modulo_id`, `modulo_status`, `modulo_nome`, `modulo_core`, `modulo_ordem`, `modulo_config_options`, `modulo_tpl_public`, `modulo_tpl_admin`, `modulo_dir`, `modulo_menu_admin`) VALUES
(1, 1, 'slideshow', 'SlideModel', 0, '{"status":"1","perpage":"30","home-perpage":"12","btn-link":"Clique aqui e saiba mais"}', NULL, NULL, NULL, NULL),
(2, 0, 'noticia', 'NoticiaModel', 0, '{"status":"1","perpage":"30","home-perpage":"6","titulo":"NotÃ­cias","subtitulo":"Fique por dentro e saia na frente da concorrÃªncia!","page-bg":""}', NULL, NULL, NULL, NULL),
(3, 0, 'blog', 'BlogModel', 0, '{"status":"0","perpage":"30","home-perpage":"6","btn-more":"leia mais",btn-all":"todos os posts","titulo":"Blog","subtitulo":""}', NULL, NULL, NULL, NULL),
(4, 1, 'produto', 'ProdutoModel', 0, '{"status":"0","perpage":"12","home-perpage":"10","top-menu-perpage":"10","btn-link-top":"Nossos Produtos","btn-link-menu":"Produtos","btn-more":"detalhes","btn-all":"Todos os Produtos","titulo-home":"<span class=color-1>Produtos</span> em Destaque","subtitulo-home":"Produtos em destaque na Oxygen.","titulo":"<span class=color-1>Nossos</span> Produtos","subtitulo":"ConheÃ§as Nossas SoluÃ§Ãµes Para Seu NegÃ³cio.","zoom-texto":"ampliar","comprar-texto":"Comprar","preco-texto":"PreÃ§o","moeda":"","mais-info-texto":"Solicitar InformaÃ§Ãµes","btn-mais-info":"Enviar Mensagem","relacionado-texto":"Veja outros produtos na categoria: ","nenhum-produto":"Nenhum produto cadastrado!","mod-orcamento":"0","mod-compra":"false","show-menu-top":"1"}', NULL, NULL, NULL, NULL),
(5, 1, 'servico', 'ServicoModel', 0, '{"status":"0","perpage":"12","home-perpage":"4","top-menu-perpage":"10","btn-more":"Saiba mais","btn-all":"Todos os ServiÃ§os","btn-link-menu":"ServiÃ§os","titulo":"<span class=color-1>Nossos</span> ServiÃ§os","subtitulo":"Saiba como podemos ajudar sua empresa ou negÃ³cio.","show-menu-top":"1"}', NULL, NULL, NULL, NULL),
(6, 1, 'cliente', 'ClienteModel', 0, '{"status":"1","perpage":"100","home-perpage":"15","right-menu-perpage":"15","categorias-perpage":"12","home-titulo": "Veja quem estÃ¡ no MacaÃ­ba Online", "btn-all":"ver todos","categorias-titulo":"CADASTROS RECENTES NO SITE"}', NULL, NULL, NULL, NULL),
(7, 1, 'social', 'SocialModel', 0, '{"status":"1","core":"SocialModel","layout":"servico"}', NULL, NULL, NULL, NULL),
(8, 1, 'contato-home', 'ContatoModel', 0, '{"status":"1","titulo":"<span class=color-1>Entre</span> em Contato","subtitulo":"Nos envie uma mensagem, vamos trocar algumas ideias.","btn-send":"Enviar Mensagem","mask-tel":"(99) 9999-99990","mask-cel":"(99) 999-999-999"}', NULL, NULL, NULL, NULL),
(9, 1, 'cliente-depoimento', 'ClienteModel', 0, '{"status":"1","show-home":"1","home-perpage":"10","perpage":"30","front-bg":"page-bg dark-bg","titulo":"Depoimentos","subtitulo":"Veja o que alguns de nossos clientes tem a dizer sobre nossos servicos."}', NULL, NULL, NULL, NULL),
(10, 0, 'newsletter', 'NewsModel', 0, '{"status":"0"}', NULL, NULL, NULL, NULL),
(11, 1, 'galeria', 'GaleriaModel', 0, '{"status":"0","perpage":"30","home-perpage":"10","btn-more":"detalhes","btn-all":"Todos os Projetos","titulo":"<span class=color-1>PorftÃ³lio </span>e Projetos","subtitulo":"Veja o que temos feito e conheÃ§a alguns de nossos trabalhos e projetos...","nenhuma-galeria":"Nenhum item disponÃ­vel!","modulo-nome":"PorfÃ³lio","posicao-desc":"1","videos-col":"4","videos-w":"370","videos-h":"280"}', NULL, NULL, NULL, NULL),
(12, 1, 'facebook', 'SocialModel', 0, '{"status":"1","app_link":"https://facebook.com/web4all.com.br","app_id":"446742768704668","app_color":"dark","app_faces":"true","app_width":"99%","app_height":"200px","titulo":"Siga-nos no Facebook","subtitulo":"junte-se Ã  nos"}', NULL, NULL, NULL, NULL),
(13, 1, 'faq', 'FaqModel', 0, '{"status":"0","perpage":"30","home-perpage":"6","top-menu-perpage":"10","titulo":"<span class=color-1>Perguntas</span> Frequentes","subtitulo":"Encontre respostas para suas dÃºvidas","lista-titulo":"Tire Suas DÃºvidas"}', NULL, NULL, NULL, NULL),
(14, 1, 'switch-color', 'ColorModel', 0, '{"status":"1"}', NULL, NULL, NULL, NULL),
(15, 1, 'contato', 'ContatoModel', 0, '{"status":"1","titulo":"<span class=color-1>Entre</span> em Contato","subtitulo":"Nos envie uma mensagem, vamos trocar algumas ideias.","btn-send":"Enviar Mensagem","mask-tel":"(99) 9999-99990","mask-cel":"999-999-999","label-nome":"Nome","placeholder-nome":"Informe seu nome","label-email":"E-mail","placeholder-email":"Informe seu e-mail","label-telefone":"Telefone","placeholder-telefone":"Informe um telefone de contato","placeholder-assunto":"Informe o assunto da mensagem","label-assunto":"Assunto","label-mensagem":"Mensagem","placeholder-mensagem":"Digite a mensagem que deseja nos enviar","telefone-requerido":"required","form-titulo":"Preencha Nosso FormulÃ¡rio de Contato"}', NULL, NULL, NULL, NULL),
(16, 1, 'pagina', 'PaginaModel', 0, '{"status":"1","perpage":"30","home-perpage":"12","titulo":"","subtitulo":""}', NULL, NULL, NULL, NULL),
(17, 0, 'stats', 'StatsModel', 0, '{"status":"0"}', NULL, NULL, NULL, NULL),
(18, 0, 'modulo', 'ModuloModel', 0, '{"status":"1"}', NULL, NULL, NULL, NULL),
(19, 1, 'ga', 'GaModel', 0, '{"status":"1"}', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ncategoria`
--

CREATE TABLE IF NOT EXISTS `ncategoria` (
  `ncategoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `ncategoria_nome` varchar(200) DEFAULT NULL,
  `ncategoria_url` varchar(200) DEFAULT NULL,
  `ncategoria_pos` int(11) DEFAULT '0',
  PRIMARY KEY (`ncategoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `ncategoria`
--

INSERT INTO `ncategoria` (`ncategoria_id`, `ncategoria_nome`, `ncategoria_url`, `ncategoria_pos`) VALUES
(1, 'Mundo', 'mundo', 0),
(2, 'Trends', 'trends', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `noticia`
--

CREATE TABLE IF NOT EXISTS `noticia` (
  `noticia_id` int(11) NOT NULL AUTO_INCREMENT,
  `noticia_titulo` varchar(200) DEFAULT NULL,
  `noticia_texto` longtext,
  `noticia_foto` varchar(200) DEFAULT NULL,
  `noticia_ncategoria` int(11) DEFAULT NULL,
  `noticia_url` varchar(200) DEFAULT NULL,
  `noticia_capa` varchar(200) DEFAULT NULL,
  `noticia_data` varchar(200) DEFAULT NULL,
  `noticia_meta_keywords` text,
  `noticia_meta_desc` text,
  `noticia_click_view` int(11) NOT NULL DEFAULT '0',
  `noticia_click_uniq` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`noticia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `noticia`
--

INSERT INTO `noticia` (`noticia_id`, `noticia_titulo`, `noticia_texto`, `noticia_foto`, `noticia_ncategoria`, `noticia_url`, `noticia_capa`, `noticia_data`, `noticia_meta_keywords`, `noticia_meta_desc`, `noticia_click_view`, `noticia_click_uniq`) VALUES
(1, 'PHPLephant Tecnologia de Ponta', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br>\r\n</p>', NULL, 1, 'phplephant-tecnologia-de-ponta', 'a8019337ca69e5d94058c5fec29e1b30.jpg', '22/09/2014', '', '', 2, 2),
(3, 'Oxigen - Gerenciador de ConteÃºdo', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', NULL, 1, 'oxigen-gerenciador-de-conteudo', '8cd1841283b6e5156e659115ca6a5647.jpg', '22/09/2014', NULL, NULL, 0, 3),
(4, 'NotÃ­cia sem foto de capa', '<p>eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br></p><p><img alt="" src="http://localhost/oxygen/midia/editor/54391.jpg" style="float: left; margin: 0px 10px 10px 0px; width: 373.887892376682px; height: 277px; cursor: default;" unselectable="on">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p><p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.&nbsp;<span rel="pastemarkerend" id="pastemarkerend3680"></span>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.&nbsp;<img alt="" src="http://localhost/oxygen/midia/editor/screen.png" style="float: right; margin: 0px 0px 10px 10px; width: 540.8px; height: 338px; cursor: default;" unselectable="on">Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<span rel="pastemarkerend" id="pastemarkerend73841"></span>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<span rel="pastemarkerend" id="pastemarkerend99248"></span><span style="line-height: 1.5em;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</span><span style="line-height: 1.5em;">&nbsp;</span><span style="line-height: 1.5em;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</span><span style="line-height: 1.5em;">&nbsp;</span><span style="line-height: 1.5em;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</span><span style="line-height: 1.5em;">&nbsp;</span></p>', NULL, 1, 'noticia-sem-foto-de-capa', '4d097dd80a5034aa926889b8e1383ee4.jpg', '22/09/2014', '', '', 0, 3),
(5, 'Nova Tecnologia no Ar', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p><p><br></p>', NULL, 0, 'nova-tecnologia-no-ar', 'd5da952a130e9c3ee5c90a9a5deaba84.jpg', '22/09/2014', 'keys', 'desc', 13, 137),
(6, 'Novo Guia Online', '<p style="text-align: justify; ">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br></p>', NULL, 2, 'novo-guia-online', '44ef69ec7d13fb98d19ae7c6f77cf7fb.jpg', '06/01/2015 13:17', 'guia, novo guia, lanÃ§amento', 'lanÃ§amento do guia online template cms', 7, 4),
(7, 'Ultimas da semana', '<p style="text-align: justify; ">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br></p>', NULL, 2, 'ultimas-da-semana', '97f5ca9529ca670697cc1336339d206e.jpg', '06/01/2015 13:19', 'novidade, extra, urgente', 'tudo que aconteceu na cidade', 6, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagina`
--

CREATE TABLE IF NOT EXISTS `pagina` (
  `pagina_id` int(11) NOT NULL AUTO_INCREMENT,
  `pagina_nome` varchar(200) DEFAULT NULL,
  `pagina_url` varchar(200) DEFAULT NULL,
  `pagina_texto` longtext,
  `pagina_area` varchar(200) DEFAULT NULL,
  `pagina_logo` varchar(200) DEFAULT NULL,
  `pagina_meta_keywords` varchar(1000) DEFAULT NULL,
  `pagina_meta_desc` varchar(2000) DEFAULT NULL,
  `pagina_meta_title` varchar(200) DEFAULT NULL,
  `pagina_comentario` int(11) NOT NULL DEFAULT '1',
  `pagina_click_view` int(11) NOT NULL DEFAULT '0',
  `pagina_click_uniq` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pagina_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Extraindo dados da tabela `pagina`
--

INSERT INTO `pagina` (`pagina_id`, `pagina_nome`, `pagina_url`, `pagina_texto`, `pagina_area`, `pagina_logo`, `pagina_meta_keywords`, `pagina_meta_desc`, `pagina_meta_title`, `pagina_comentario`, `pagina_click_view`, `pagina_click_uniq`) VALUES
(22, 'ServiÃ§o 01', 'servico-01', '<p style="text-align: justify;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<span rel="pastemarkerend" id="pastemarkerend71523"></span><br>\r\n</p>\r\n<p style="text-align: justify;"><br>\r\n</p>', '1', NULL, '', '', NULL, 1, 11, 10),
(23, 'ServiÃ§o 02', 'servico-02', '<p style="text-align: justify;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<span rel="pastemarkerend" id="pastemarkerend91335"></span><br>\r\n</p>', '1', NULL, '', '', NULL, 1, 5, 4),
(24, 'DÃºvida 01', 'duvida-01', '<div style="text-align: justify;"><span style="line-height: 1.5em;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span></div>', '10', NULL, '', '', NULL, 1, 0, 2),
(25, 'Quem Somos', 'quem-somos', '<p style="text-align: justify;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<span rel="pastemarkerend" id="pastemarkerend37901"></span><br>\r\n</p>', '2', NULL, 'lova virtual, webiste, download', 'Dsenvolvemos sites dinÃ¢micos, lojas virtuais seguras e bla bla', NULL, 1, 7, 6),
(26, 'LocalizaÃ§Ã£o', 'localizacao', '<p style="text-align: justify;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<span rel="pastemarkerend" id="pastemarkerend17016"></span><br>\r\n</p>', '2', NULL, '', '', NULL, 1, 12, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pfoto`
--

CREATE TABLE IF NOT EXISTS `pfoto` (
  `foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `foto_url` varchar(200) DEFAULT NULL,
  `foto_produto` varchar(200) DEFAULT NULL,
  `foto_pos` int(11) DEFAULT '0',
  `foto_nome` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`foto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Extraindo dados da tabela `pfoto`
--

INSERT INTO `pfoto` (`foto_id`, `foto_url`, `foto_produto`, `foto_pos`, `foto_nome`) VALUES
(16, '93d59d1f39b55d02934f0dd8008f383b.jpg', '3', 1, NULL),
(17, '43e947f6450ebf6891e79ceb663fb591.jpg', '2', 0, NULL),
(18, '85052ddb20eaecc940674b29cc35869a.jpg', '1', 0, NULL),
(19, 'fa0bd5a0c3290ac7a39fd4e2cc77abfe.jpg', '3', 2, NULL),
(21, 'cdc5655e5424a832a261d71af42463d3.jpg', '5', 1, NULL),
(22, 'c9fef4fc25355ab27414aca875e3dda4.jpg', '3', 0, NULL),
(23, '334d9703b51544d4e0cb479c0f893835.jpg', '3', 3, NULL),
(24, '1ae0d922c35a730da512e3df9c4c5d5a.jpg', '5', 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE IF NOT EXISTS `produto` (
  `produto_id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_nome` varchar(200) DEFAULT NULL,
  `produto_categoria` int(11) DEFAULT NULL,
  `produto_descricao` longtext,
  `produto_valor` varchar(200) DEFAULT NULL,
  `produto_codigo` varchar(20) DEFAULT NULL,
  `produto_url` varchar(200) DEFAULT NULL,
  `produto_show` int(11) NOT NULL DEFAULT '1',
  `produto_ativo` int(11) NOT NULL DEFAULT '1',
  `produto_meta_keywords` text,
  `produto_meta_desc` text,
  `produto_click_view` int(11) NOT NULL DEFAULT '0',
  `produto_click_uniq` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`produto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`produto_id`, `produto_nome`, `produto_categoria`, `produto_descricao`, `produto_valor`, `produto_codigo`, `produto_url`, `produto_show`, `produto_ativo`, `produto_meta_keywords`, `produto_meta_desc`, `produto_click_view`, `produto_click_uniq`) VALUES
(1, 'Sistema 01', 1, '<div style="text-align: justify;"><span style="line-height: 1.42857143;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span></div>', '100.00', '01', 'sistema-01', 1, 1, NULL, NULL, 0, 1),
(2, 'Sistema 02', 2, '<p style="text-align: justify; ">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '200.00', '02', 'sistema-02', 1, 1, NULL, NULL, 0, 3),
(3, 'Sistema 03', 3, '<div style="text-align: justify;"><span style="line-height: 1.42857143;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span></div>', '150.00', '03', 'sistema-03', 1, 1, '', '', 1, 7),
(5, 'Sistema 04', 3, '<p style="text-align: justify; ">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.s<br></p>', '', '04', 'sistema-04', 1, 1, 'teste', 'teste', 0, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `rota`
--

CREATE TABLE IF NOT EXISTS `rota` (
  `rota_id` int(11) NOT NULL AUTO_INCREMENT,
  `rota_url` varchar(500) DEFAULT NULL,
  `rota_controle` varchar(100) DEFAULT NULL,
  `rota_acao` varchar(100) DEFAULT NULL,
  `rota_item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`rota_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `rota`
--

INSERT INTO `rota` (`rota_id`, `rota_url`, `rota_controle`, `rota_acao`, `rota_item_id`) VALUES
(1, 'atendimento/perguntas-frequentes/', 'faq', '', 0),
(2, 'atendimento/fale-conosco/', 'contato', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE IF NOT EXISTS `servico` (
  `servico_id` int(11) NOT NULL AUTO_INCREMENT,
  `servico_titulo` varchar(200) DEFAULT NULL,
  `servico_texto` longtext,
  `servico_foto` varchar(200) DEFAULT NULL,
  `servico_url` varchar(200) DEFAULT NULL,
  `servico_capa` varchar(200) DEFAULT NULL,
  `servico_pos` varchar(200) NOT NULL DEFAULT '0',
  `servico_meta_keywords` text,
  `servico_meta_desc` text,
  `servico_click_view` int(11) NOT NULL DEFAULT '0',
  `servico_click_uniq` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`servico_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `servico`
--

INSERT INTO `servico` (`servico_id`, `servico_titulo`, `servico_texto`, `servico_foto`, `servico_url`, `servico_capa`, `servico_pos`, `servico_meta_keywords`, `servico_meta_desc`, `servico_click_view`, `servico_click_uniq`) VALUES
(2, 'Parceiro 04', '<p style="line-height: 18.5714302062988px;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.&nbsp;</p><p style="line-height: 18.5714302062988px;"><br></p><p style="line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span></p>', NULL, 'parceiro-04', '736a4287af687e1e3aeec1b308b0c7ce.png', '2', '', '', 0, 4),
(3, 'Parceiro 03', '<p style="text-align: center; line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px; text-align: justify; font-weight: bold; font-size: 18px;"><br></span></p><p style="text-align: center; line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px; text-align: justify; font-weight: bold; font-size: 18px; color: rgb(8, 82, 148);">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</span><br></p><p style="text-align: justify; "><span style="color: rgb(99, 99, 99);">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span><br></p><p style="text-align: center; line-height: 18.5714302062988px;"></p><p style="text-align: justify; line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px; font-family: ''Open Sans''; color: rgb(99, 99, 99);">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et &nbsp;accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur &nbsp;sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea &nbsp;rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod &nbsp;tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea &nbsp;takimata sanctus est Lorem ipsum dolor sit amet.</span></p><p style="text-align: justify; line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px; font-family: ''Open Sans''; color: rgb(99, 99, 99);">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur &nbsp;sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea &nbsp;rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod &nbsp;tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea &nbsp;takimata sanctus est Lorem ipsum dolor sit amet.</span><span style="line-height: 18.5714302062988px;"><br></span></p>', NULL, 'parceiro-03', '50a291a9d5c259a69541f7224ccb04cf.png', '1', '', '', 0, 2),
(4, 'Parceiro 02', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.&nbsp;</p><p></p><p><br></p><p><span style="line-height: 18.5714302062988px;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span><br></p>', NULL, 'parceiro-02', 'cd94791e5d89c1401df2693db62c79e0.png', '0', '', '', 2, 5),
(5, 'Parceiro 01', '<p style="line-height: 18.5714302062988px;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.&nbsp;</p><p style="line-height: 18.5714302062988px;"><br></p><p style="line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</span></p><p style="line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px;"><br></span></p><p style="line-height: 18.5714302062988px;"><span style="line-height: 18.5714302062988px;"><br></span></p><p>teste</p><p><br></p><p><br></p>', NULL, 'parceiro-01', '8884ef2326ccd8e8c4ced6e904328db2.png', '3', 'teste', 'teste', 2, 13);

-- --------------------------------------------------------

--
-- Estrutura da tabela `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `site_tema` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `site_color` varchar(200) DEFAULT 'light',
  `site_slogan` varchar(200) DEFAULT NULL,
  `site_about` longtext,
  `site_meta_desc` text,
  `site_meta_autor` varchar(200) DEFAULT NULL,
  `site_meta_title` varchar(200) DEFAULT NULL,
  `site_favicon` varchar(200) DEFAULT NULL,
  `site_logo` varchar(200) DEFAULT NULL,
  `site_logo_sm` varchar(200) CHARACTER SET dec8 DEFAULT NULL,
  `site_logo_x` int(11) DEFAULT NULL,
  `site_logo_y` int(11) DEFAULT NULL,
  `site_google_analytics` varchar(200) DEFAULT NULL,
  `site_meta_keywords` text,
  `site_template` varchar(200) DEFAULT NULL,
  `site_template_skin` varchar(200) DEFAULT '0',
  `site_template_box` varchar(200) DEFAULT NULL,
  `site_template_bg` varchar(200) DEFAULT NULL,
  `site_template_pattern` varchar(200) DEFAULT NULL,
  `site_agencia_snippet` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `site`
--

INSERT INTO `site` (`site_id`, `site_title`, `site_tema`, `site_color`, `site_slogan`, `site_about`, `site_meta_desc`, `site_meta_autor`, `site_meta_title`, `site_favicon`, `site_logo`, `site_logo_sm`, `site_logo_x`, `site_logo_y`, `site_google_analytics`, `site_meta_keywords`, `site_template`, `site_template_skin`, `site_template_box`, `site_template_bg`, `site_template_pattern`, `site_agencia_snippet`) VALUES
(1, 'MacaÃ­ba Online', 'oxygen', 'blue', 'Gerenciador de Conteudo', '<div class="col-sm-4" style="color: rgb(119, 119, 119); font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 22px; background-color: rgb(255, 255, 255);"><h2 style="font-family: ''Open Sans'', sans-serif; line-height: 24px; margin: 0px 0px 17px; font-size: 24px; padding: 0px; color: rgb(136, 136, 136);"><img src="data:image/png;base64,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" style="padding: 5px; width: 250px; height: 286.671875px; float: right;" data-filename="services.png"></h2>\r\n</div>\r\n<div class="col-sm-4" style="color: rgb(119, 119, 119); font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 22px;"><h2 style="font-family: ''Open Sans'', sans-serif; line-height: 24px; margin: 0px 0px 17px; font-size: 24px; padding: 0px; color: rgb(136, 136, 136); background-color: rgb(255, 255, 255);">Tenha sesu website agora!</h2>\r\n<p style="margin-bottom: 12px;">This did not seem to encourage the witness at all: he kept shifting from one foot to the other, looking uneasily at the Queen, and in his confusion he bit a large piece out of his teacup instead of the bread-and-butter.</p>\r\n<p style="margin-bottom: 12px;">Just at this moment Alice felt a very curious sensation, which puzzled her a good deal until she made out what it was: she was beginning to grow larger again, and she thought at first she would get up and leave the court; but on second thoughts she decided to remain where she was as long as there was room for her.</p>\r\n</div>\r\n<div class="col-sm-4" style="font-size: 16px; line-height: 22px;"><h2 style="font-family: ''Open Sans'', sans-serif; line-height: 24px; margin: 0px 0px 17px; font-size: 24px; padding: 0px;"><span style="color: rgb(57, 132, 198);">OXY</span><span style="color: rgb(136, 136, 136); background-color: rgb(255, 255, 255);">&nbsp;- SoluÃ§Ãµes para Web</span></h2>\r\n<p style="font-family: Arial, Helvetica, sans-serif; color: rgb(119, 119, 119); margin-bottom: 12px; background-color: rgb(255, 255, 255);">The Hatter''s remark seemed to have no sort of meaning in it, and yet it was certainly English. ''I don''t quite understand you,'' she said, as politely as she could. ''The Dormouse is asleep again,'' said the Hatter, and he poured a little hot tea upon its nose.</p>\r\n<h3 style="line-height: 22px; margin: 0px 0px 14px; font-size: 18px; letter-spacing: -0.06em;"><span style="color: rgb(119, 119, 119); font-family: Arial, Helvetica, sans-serif; font-size: 16px; background-color: rgb(255, 255, 255);">The Dormouse shook its head impatiently, and said, without opening its eyes, ''Of course, of course; just what I was going to remark myself.'' ''Have you guessed the riddle yet?'' the Hatter said, turning to Alice again.</span><br></h3>\r\n</div>', 'Sistema Gerenciador de ConteÃºdo para Sites institucionais, blogs, pÃ¡ginas pessoais e etc...', 'Rafael Clares', 'Oxygen', NULL, 'main-logo.png', NULL, -10, -1, 'UA-18596587-7', 'webdesign, developer, hosting, sistemas web', 'guia', '7', 'boxed', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `slide`
--

CREATE TABLE IF NOT EXISTS `slide` (
  `slide_id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_pos` int(3) DEFAULT '999',
  `slide_url` varchar(200) DEFAULT NULL,
  `slide_titulo` varchar(200) DEFAULT NULL,
  `slide_msg` varchar(200) DEFAULT NULL,
  `slide_link` varchar(200) DEFAULT NULL,
  `slide_local` int(5) DEFAULT '1',
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Extraindo dados da tabela `slide`
--

INSERT INTO `slide` (`slide_id`, `slide_pos`, `slide_url`, `slide_titulo`, `slide_msg`, `slide_link`, `slide_local`) VALUES
(7, 4, '24ee350d31db72104f5f596a0d67cb7f.jpg', '', '', '', 1),
(8, 2, '4fee5027ace180a74f76ade0af96ca84.jpg', '', '', '', 1),
(10, 3, '66a293cf284b82144ef87c0eb4b5d80a.jpg', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet', '', 1),
(11, 2, '15ed32baea6392c1955b3fef45c07a95.jpg', 'Cliente tal', NULL, 'http://google.com.br', 3),
(12, 1, 'd2787e3a10dcdf69111a335c54be58e2.jpg', 'Novo', NULL, '', 2),
(13, 0, '8124a25c0d0b554d2c5c750558835436.jpg', 'teste', NULL, '', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `smtp`
--

CREATE TABLE IF NOT EXISTS `smtp` (
  `smtp_id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_port` varchar(10) DEFAULT NULL,
  `smtp_user` varchar(200) DEFAULT NULL,
  `smtp_pass` varchar(200) DEFAULT NULL,
  `smtp_tls` int(11) DEFAULT NULL,
  `smtp_bcc` varchar(200) DEFAULT NULL,
  `smtp_from` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`smtp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `smtp`
--

INSERT INTO `smtp` (`smtp_id`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `smtp_tls`, `smtp_bcc`, `smtp_from`) VALUES
(1, 'mail.seusite.com.br', '587', 'contato@seusite.com.br', '123456', 0, '', 'Contato');

-- --------------------------------------------------------

--
-- Estrutura da tabela `social`
--

CREATE TABLE IF NOT EXISTS `social` (
  `social_id` int(11) NOT NULL AUTO_INCREMENT,
  `social_facebook` varchar(200) DEFAULT NULL,
  `social_twitter` varchar(200) DEFAULT NULL,
  `social_linkedin` varchar(200) DEFAULT NULL,
  `social_instagram` varchar(200) DEFAULT NULL,
  `social_plus` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`social_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `social`
--

INSERT INTO `social` (`social_id`, `social_facebook`, `social_twitter`, `social_linkedin`, `social_instagram`, `social_plus`) VALUES
(1, 'http://facebook.com/clares.lab', 'http://twitter.com/clares.lab', '', '', 'http://plus.google.com/clares.lab');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subgrupo`
--

CREATE TABLE IF NOT EXISTS `subgrupo` (
  `subgrupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `subgrupo_grupo` int(11) DEFAULT NULL,
  `subgrupo_nome` varchar(200) DEFAULT NULL,
  `subgrupo_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`subgrupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tema`
--

CREATE TABLE IF NOT EXISTS `tema` (
  `tema_id` int(11) NOT NULL AUTO_INCREMENT,
  `tema_nome` varchar(200) DEFAULT NULL,
  `tema_dir` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`tema_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tema`
--

INSERT INTO `tema` (`tema_id`, `tema_nome`, `tema_dir`) VALUES
(1, 'padrao', 'padrao'),
(2, 'guia', 'guia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `topico`
--

CREATE TABLE IF NOT EXISTS `topico` (
  `topico_id` int(11) NOT NULL AUTO_INCREMENT,
  `topico_nome` text,
  `topico_pos` int(3) DEFAULT '0',
  PRIMARY KEY (`topico_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `topico`
--

INSERT INTO `topico` (`topico_id`, `topico_nome`, `topico_pos`) VALUES
(1, 'Topico 01', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_nome` varchar(200) DEFAULT NULL,
  `usuario_login` varchar(200) DEFAULT NULL,
  `usuario_senha` varchar(200) DEFAULT NULL,
  `usuario_email` varchar(200) DEFAULT NULL,
  `usuario_fone` varchar(20) DEFAULT NULL,
  `usuario_nivel` int(3) DEFAULT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_nome`, `usuario_login`, `usuario_senha`, `usuario_email`, `usuario_fone`, `usuario_nivel`) VALUES
(1, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'seuemail@gmail.com', '', 1);

-- --------------------------------------------------------



CREATE TABLE IF NOT EXISTS `versao` (
  `versao_id` int(11) NOT NULL AUTO_INCREMENT,
  `versao_num` int(11) DEFAULT NULL,
  `versao_num_info` varchar(200) DEFAULT NULL,
  `versao_data` varchar(200) DEFAULT NULL,
  `versao_log` text,
  PRIMARY KEY (`versao_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;



INSERT INTO `versao` (`versao_id`, `versao_num`, `versao_num_info`, `versao_data`, `versao_log`) VALUES
(1, 1, '1.0.0', '24/12/2014', '**************** Begin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_titulo` varchar(200) DEFAULT NULL,
  `video_url` varchar(200) DEFAULT NULL,
  `video_mirror` varchar(200) DEFAULT NULL,
  `video_galeria` int(11) DEFAULT NULL,
  `video_pos` int(11) DEFAULT '0',
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `video`
--

INSERT INTO `video` (`video_id`, `video_titulo`, `video_url`, `video_mirror`, `video_galeria`, `video_pos`) VALUES
(4, '', 'https://www.youtube.com/watch?v=Nu4UdkjBXeo', '2', 4, 1),
(5, '', 'http://vimeo.com/7778895', '1', 4, 0),
(9, '', 'www.youtube.com/watch?v=wHaRAfxLd7g', '2', 4, 2);

