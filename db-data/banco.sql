-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 26-Fev-2020 às 08:35
-- Versão do servidor: 5.7.26
-- versão do PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shift`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `exame`
--

DROP TABLE IF EXISTS `exame`;
CREATE TABLE IF NOT EXISTS `exame` (
  `idExame` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `preco` double NOT NULL,
  PRIMARY KEY (`idExame`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `exame`
--

INSERT INTO `exame` (`idExame`, `descricao`, `preco`) VALUES
(1, 'Exame de Urina', 100),
(2, 'Exame de Sangue', 50),
(3, 'Ultrasom', 200),
(6, 'RaioX', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `medico`
--

DROP TABLE IF EXISTS `medico`;
CREATE TABLE IF NOT EXISTS `medico` (
  `idMedico` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `especialidade` varchar(255) NOT NULL,
  PRIMARY KEY (`idMedico`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `medico`
--

INSERT INTO `medico` (`idMedico`, `nome`, `especialidade`) VALUES
(1, 'Dr Wesley Barboza', 'cirurgião'),
(3, 'Dra Solange Lima', 'Cardiologia'),
(4, 'Dr Guilherme Silva', 'Pediatria'),
(5, 'Dra Gabriela Barbosa', 'Ginecologia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordemservico`
--

DROP TABLE IF EXISTS `ordemservico`;
CREATE TABLE IF NOT EXISTS `ordemservico` (
  `idOrdemServico` int(11) NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL,
  `idPaciente` int(11) NOT NULL,
  `convenio` varchar(255) NOT NULL,
  `idPostoColeta` int(11) NOT NULL,
  `idMedico` int(11) NOT NULL,
  PRIMARY KEY (`idOrdemServico`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idPostoColeta` (`idPostoColeta`),
  KEY `idMedico` (`idMedico`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `ordemservico`
--

INSERT INTO `ordemservico` (`idOrdemServico`, `data`, `idPaciente`, `convenio`, `idPostoColeta`, `idMedico`) VALUES
(1, '2020-02-11 03:00:00', 1, 'UNIMED', 1, 3),
(3, '2020-02-11 03:00:00', 2, 'HB', 2, 3),
(11, '2020-02-11 03:00:00', 1, 'UNIMED', 4, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordemservicoexame`
--

DROP TABLE IF EXISTS `ordemservicoexame`;
CREATE TABLE IF NOT EXISTS `ordemservicoexame` (
  `idOrdemServico` int(11) NOT NULL,
  `idExame` int(11) NOT NULL,
  `preco` double DEFAULT NULL,
  KEY `idOrdemServico` (`idOrdemServico`),
  KEY `idExame` (`idExame`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `ordemservicoexame`
--

INSERT INTO `ordemservicoexame` (`idOrdemServico`, `idExame`, `preco`) VALUES
(1, 1, NULL),
(1, 2, NULL),
(3, 3, NULL),
(11, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `paciente`
--

DROP TABLE IF EXISTS `paciente`;
CREATE TABLE IF NOT EXISTS `paciente` (
  `idPaciente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `datanascimento` date NOT NULL,
  `sexo` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  PRIMARY KEY (`idPaciente`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `paciente`
--

INSERT INTO `paciente` (`idPaciente`, `nome`, `datanascimento`, `sexo`, `endereco`) VALUES
(1, 'Rodrigo Lima', '2020-02-04', 'masculino', 'Av Bady Bassit 252'),
(2, 'Leonardo', '2007-03-03', 'masculino', 'Rua das ruas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `postocoleta`
--

DROP TABLE IF EXISTS `postocoleta`;
CREATE TABLE IF NOT EXISTS `postocoleta` (
  `idPostoColeta` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `endereco` varchar(255) NOT NULL,
  PRIMARY KEY (`idPostoColeta`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `postocoleta`
--

INSERT INTO `postocoleta` (`idPostoColeta`, `descricao`, `endereco`) VALUES
(1, 'Laboratório de Exames Físicos', 'Av Alberto Andaló 111'),
(2, 'UMA', 'Rod BR-153'),
(3, 'UltraX', 'Av Vetorazzo 333'),
(4, 'Tajara', 'Av dos Estudantes 487');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `ordemservico`
--
ALTER TABLE `ordemservico`
  ADD CONSTRAINT `fkMedico` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`),
  ADD CONSTRAINT `fkPaciente` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`),
  ADD CONSTRAINT `fkPostoColeta` FOREIGN KEY (`idPostoColeta`) REFERENCES `postocoleta` (`idPostoColeta`);

--
-- Limitadores para a tabela `ordemservicoexame`
--
ALTER TABLE `ordemservicoexame`
  ADD CONSTRAINT `fkExame` FOREIGN KEY (`idExame`) REFERENCES `exame` (`idExame`),
  ADD CONSTRAINT `fkOrdermServico` FOREIGN KEY (`idOrdemServico`) REFERENCES `ordemservico` (`idOrdemServico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
