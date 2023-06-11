-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2023 at 10:40 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pds`
--

-- --------------------------------------------------------

--
-- Table structure for table `daerah`
--

CREATE TABLE `daerah` (
  `iddaerah` int(11) NOT NULL,
  `daerah` varchar(255) NOT NULL,
  `negara` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `daerah`
--

INSERT INTO `daerah` (`iddaerah`, `daerah`, `negara`) VALUES
(1, 'Washington DC', 5);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kejahatan`
--

CREATE TABLE `jenis_kejahatan` (
  `idjenis` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `gambar` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_kejahatan`
--

INSERT INTO `jenis_kejahatan` (`idjenis`, `nama`, `gambar`) VALUES
(1, 'THEFT/OTHER', 'theft.png'),
(2, 'MOTOR VEHICLE THEFT', 'vehicletheft.png'),
(3, 'BURGLARY', 'burglary.png'),
(4, 'ROBBERY', 'robbery.png'),
(5, 'ASSAULT W/DANGEROUS WEAPON', 'assault.png'),
(6, 'THEFT F/AUTO', 'theftf.png'),
(7, 'SEX ABUSE', 'sexabuse.png'),
(8, 'HOMICIDE', 'homicide.png'),
(9, 'ARSON', 'arson.png');

-- --------------------------------------------------------

--
-- Table structure for table `negara`
--

CREATE TABLE `negara` (
  `idnegara` int(11) NOT NULL,
  `namanegara` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `negara`
--

INSERT INTO `negara` (`idnegara`, `namanegara`) VALUES
(1, 'Afghanistan'),
(2, 'Afrika Selatan'),
(3, 'Albania'),
(4, 'Aljazair'),
(5, 'Amerika Serikat'),
(6, 'Andorra'),
(7, 'Angola'),
(8, 'Antigua & Barbuda'),
(9, 'Arab Saudi'),
(10, 'Argentina'),
(11, 'Armenia'),
(12, 'Australia'),
(13, 'Austria'),
(14, 'Azerbaijan'),
(15, 'Bahama'),
(16, 'Bahrain'),
(17, 'Bangladesh'),
(18, 'Barbados'),
(19, 'Belanda'),
(20, 'Belarusia'),
(21, 'Belgia'),
(22, 'Belize'),
(23, 'Benin'),
(24, 'Bhutan'),
(25, 'Bolivia'),
(26, 'Bosnia & Herzegovina'),
(27, 'Botswana'),
(28, 'Brasil'),
(29, 'Britania Raya'),
(30, 'Brunei'),
(31, 'Bulgaria'),
(32, 'Burkina Faso'),
(33, 'Burundi'),
(34, 'Chad'),
(35, 'Chile'),
(36, 'China'),
(37, 'Denmark'),
(38, 'Djibouti'),
(39, 'Dominika'),
(40, 'Ekuador'),
(41, 'El Salvador'),
(42, 'Eritrea'),
(43, 'Estonia'),
(44, 'Eswatini'),
(45, 'Ethiopia'),
(46, 'Federasi Mikronesia'),
(47, 'Fiji'),
(48, 'Filipina'),
(49, 'Finlandia'),
(50, 'Gabon'),
(51, 'Gambia'),
(52, 'Georgia'),
(53, 'Ghana'),
(54, 'Grenada'),
(55, 'Guatemala'),
(56, 'Guinea'),
(57, 'Guinea Khatulistiwa'),
(58, 'Guinea-Bissau'),
(59, 'Guyana'),
(60, 'Haiti'),
(61, 'Honduras'),
(62, 'Hungaria'),
(63, 'India'),
(64, 'Indonesia'),
(65, 'Irak'),
(66, 'Iran'),
(67, 'Irlandia'),
(68, 'Islandia'),
(69, 'Israel'),
(70, 'Italia'),
(71, 'Jamaika'),
(72, 'Jepang'),
(73, 'Jerman'),
(74, 'Kamboja'),
(75, 'Kamerun'),
(76, 'Kanada'),
(77, 'Kazakhstan'),
(78, 'Kenya'),
(79, 'Kepulauan Marshall'),
(80, 'Kepulauan Solomon'),
(81, 'Kirgizstan'),
(82, 'Kiribati'),
(83, 'Kolombia'),
(84, 'Korea Selata'),
(85, 'Korea Utara'),
(86, 'Kosta Rika'),
(87, 'Kroasia'),
(88, 'Kuba'),
(89, 'Kuwait'),
(90, 'Laos'),
(91, 'Latvia'),
(92, 'Lebanon'),
(93, 'Lesotho'),
(94, 'Liberia'),
(95, 'Libya'),
(96, 'Liechtenstein'),
(97, 'Lithuania'),
(98, 'Luksemburg'),
(99, 'Madagaskar'),
(100, 'Makedonia'),
(101, 'Maladewa'),
(102, 'Malawi'),
(103, 'Malaysia'),
(104, 'Mali'),
(105, 'Malta'),
(106, 'Maroko'),
(107, 'Mauritania'),
(108, 'Mauritius'),
(109, 'Meksiko'),
(110, 'Mesir'),
(111, 'Moldova'),
(112, 'Monako'),
(113, 'Mongolia'),
(114, 'Montenegro'),
(115, 'Mozambik'),
(116, 'Myanmar'),
(117, 'Namibia'),
(118, 'Nauru'),
(119, 'Nepal'),
(120, 'Niger'),
(121, 'Nigeria'),
(122, 'Nikaragua'),
(123, 'Norwegia'),
(124, 'Oman'),
(125, 'Pakistan'),
(126, 'Palau'),
(127, 'Palestina'),
(128, 'Panama'),
(129, 'Pantai Gading'),
(130, 'Papua Nugini'),
(131, 'Paraguay'),
(132, 'Prancis'),
(133, 'Peru'),
(134, 'Polandia'),
(135, 'Portugal'),
(136, 'Qatar'),
(137, 'Republik Afrika Tengah'),
(138, 'Republik Ceko'),
(139, 'Republik Demokratik Kongo'),
(140, 'Republik Dominika'),
(141, 'Republik Kongo'),
(142, 'Rumania'),
(143, 'Rusia'),
(144, 'Rwanda'),
(145, 'Saint Kitts & Nevis'),
(146, 'Saint Lucia'),
(147, 'Saint Vincent & Grenadines'),
(148, 'Samoa'),
(149, 'San Marino'),
(150, 'Sao Tome & Principe'),
(151, 'Selandia Baru'),
(152, 'Senegal'),
(153, 'Serbia'),
(154, 'Seychelles'),
(155, 'Sierra Leone'),
(156, 'Singapura'),
(157, 'Siprus'),
(158, 'Slovenia'),
(159, 'Slowakia'),
(160, 'Somalia'),
(161, 'Spanyol'),
(162, 'Sri Lanka'),
(163, 'Sudan'),
(164, 'Sudan Selatan'),
(165, 'Suriah'),
(166, 'Suriname'),
(167, 'Swedia'),
(168, 'Swiss'),
(169, 'Taiwan'),
(170, 'Tajikistan'),
(171, 'Tanjung Verde'),
(172, 'Tanzania'),
(173, 'Thailand'),
(174, 'Timor Leste'),
(175, 'Togo'),
(176, 'Tonga'),
(177, 'Trinidad & Tobago'),
(178, 'Tunisia'),
(179, 'Turki'),
(180, 'Turkmenistan'),
(181, 'Tuvalu'),
(182, 'Uganda'),
(183, 'Ukraina'),
(184, 'Uni Emirat Arab'),
(185, 'Uruguay'),
(186, 'Uzbekistan'),
(187, 'Vanuatu'),
(188, 'Vatican City'),
(189, 'Venezuela'),
(190, 'Vietnam'),
(191, 'Yama'),
(192, 'Yordania'),
(193, 'Yunani'),
(194, 'Zambia'),
(195, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `nama`, `email`, `password`) VALUES
(1, 'Netizen A', 'netizena@gmail.com', 'ef7d9bb8ab1d658266294bfbb1937c6a'),
(2, 'Netizen B', 'netizenb@gmail.com', 'ef7d9bb8ab1d658266294bfbb1937c6a'),
(3, 'Netizen C', 'netizenc@gmail.com', 'ef7d9bb8ab1d658266294bfbb1937c6a'),
(4, 'Netizen D', 'netizend@gmail.com', 'ef7d9bb8ab1d658266294bfbb1937c6a'),
(5, 'Netizen E', 'netizene@gmail.com', 'ef7d9bb8ab1d658266294bfbb1937c6a'),
(6, 'Netizen F', 'netizenf@gmail.com', 'ef7d9bb8ab1d658266294bfbb1937c6a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daerah`
--
ALTER TABLE `daerah`
  ADD PRIMARY KEY (`iddaerah`);

--
-- Indexes for table `jenis_kejahatan`
--
ALTER TABLE `jenis_kejahatan`
  ADD PRIMARY KEY (`idjenis`);

--
-- Indexes for table `negara`
--
ALTER TABLE `negara`
  ADD PRIMARY KEY (`idnegara`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daerah`
--
ALTER TABLE `daerah`
  MODIFY `iddaerah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jenis_kejahatan`
--
ALTER TABLE `jenis_kejahatan`
  MODIFY `idjenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `negara`
--
ALTER TABLE `negara`
  MODIFY `idnegara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
