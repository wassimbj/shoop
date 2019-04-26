-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 08 mars 2019 à 10:55
-- Version du serveur :  10.1.35-MariaDB
-- Version de PHP :  7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `shoop`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `gender`, `created_at`) VALUES
(2, 'admin', 'admin@admin.com', '$2y$10$t84RK4ThrnSsw.UaztHjiO6TvtzYlgsT3AvP.Hli912TJeTmM2C.S', 'male', '2019-01-25 12:34:47');

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `cart_by` int(255) NOT NULL,
  `cart_to` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(26) NOT NULL,
  `color` varchar(20) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `paid` int(11) NOT NULL,
  `invoiced` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`id`, `cart_by`, `cart_to`, `quantity`, `size`, `color`, `price`, `paid`, `invoiced`, `created_at`) VALUES
(7, 2, 37, 1, 'M', 'black', '78.00', 1, 0, '2019-02-16 15:11:04'),
(8, 2, 35, 2, 'without size', 'yellow', '54.00', 1, 0, '2019-02-16 15:16:36'),
(9, 1, 38, 1, 'without size', 'grey', '25.00', 1, 0, '2019-02-26 19:15:18'),
(15, 1, 41, 1, 'without size', 'yellow', '85.00', 1, 0, '2019-03-06 16:46:09'),
(16, 1, 39, 1, 'M', 'red', '50.00', 1, 0, '2019-03-08 10:32:43'),
(17, 1, 39, 1, 'M', 'red', '50.00', 1, 0, '2019-03-08 10:44:35'),
(18, 1, 39, 1, 'M', 'red', '50.00', 0, 0, '2019-03-08 10:45:29');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `appear` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `appear`, `created_at`) VALUES
(1, 'men', 1, '2019-01-21 17:33:47'),
(2, 'women', 1, '2019-01-21 17:33:47'),
(3, 'accessoires', 1, '2019-01-21 17:34:02'),
(4, 'electronics', 1, '2019-01-22 11:31:51'),
(6, 'pets', 1, '2019-02-10 17:17:27'),
(7, 'kids', 1, '2019-02-16 18:45:18');

-- --------------------------------------------------------

--
-- Structure de la table `countries`
--

CREATE TABLE `countries` (
  `countryID` int(11) NOT NULL,
  `country_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `countries`
--

INSERT INTO `countries` (`countryID`, `country_name`) VALUES
(1, 'Albania'),
(2, 'Algeria'),
(15, 'Botswana\r\n'),
(24, 'china'),
(29, 'Cuba\r\n'),
(30, 'Czech Republic\r\n'),
(32, 'Egypt'),
(43, 'Andorra'),
(44, 'Angola'),
(45, 'Argentina'),
(46, 'Armenia'),
(47, 'Australia'),
(48, 'Austria'),
(49, 'Bahamas'),
(50, 'Bahrain'),
(51, 'Bangladesh'),
(52, 'Belarus'),
(53, 'Belgium'),
(54, 'Bolivia'),
(55, 'Botswana'),
(56, 'Brazil'),
(57, 'Bulgaria'),
(58, 'Burkina Faso'),
(59, 'Cabo Verde'),
(60, 'Cambodia\r\n'),
(61, 'Canada'),
(62, 'Chad'),
(63, 'Chile'),
(65, 'colombia'),
(66, 'Costa Rica'),
(67, 'Cote d\'Ivoire'),
(68, 'Croatia'),
(69, 'Cuba'),
(70, 'Czech Republic'),
(71, 'Ecuador'),
(73, 'Denmark'),
(74, 'Djibouti'),
(75, 'Dominica'),
(76, 'Estonia'),
(77, 'Finland'),
(78, 'France'),
(79, 'Gabon'),
(80, 'Georgia'),
(81, 'Germany'),
(82, 'Ghana'),
(83, 'Greece'),
(84, 'Guinea'),
(85, 'Honduras'),
(86, 'Hungary'),
(87, 'Iceland'),
(88, 'India'),
(89, 'Indonesia'),
(90, 'Iran'),
(91, 'Iraq'),
(92, 'Ireland'),
(93, 'Italy'),
(94, 'Jamaica'),
(95, 'Japan'),
(96, 'Jordan'),
(97, 'Kenya'),
(98, 'Kuwait'),
(99, 'Kosovo'),
(100, 'Latvia'),
(101, 'Lebanon'),
(102, 'Liberia'),
(103, 'Libiya'),
(104, 'Luxembourg'),
(105, 'Madagascar'),
(106, 'Malawi'),
(107, 'Malaysia'),
(108, 'Maldives'),
(109, 'Mali'),
(110, 'Malta'),
(111, 'Mexico'),
(112, 'Monaco'),
(113, 'Montenegro'),
(114, 'Morocco'),
(115, 'Mozambique'),
(116, 'Netherlands'),
(117, 'New Zealand'),
(118, 'Niger'),
(119, 'Nigiria'),
(120, 'North Korea'),
(121, 'Norway'),
(122, 'Oman'),
(123, 'Palestine'),
(124, 'Panama'),
(125, 'Paraguay'),
(126, 'Peru'),
(127, 'Philippines'),
(128, 'Poland'),
(129, 'Portugal'),
(130, 'Qatar'),
(131, 'Romania'),
(132, 'Russia'),
(133, 'Rawanda'),
(134, 'Saudi Arabia'),
(135, 'Senegal'),
(136, 'Serbia'),
(137, 'Singapore'),
(138, 'Slovakia'),
(139, 'Slovenia'),
(140, 'South Korea'),
(141, 'Spain'),
(142, 'Sudan'),
(143, 'Sweden'),
(144, 'Switzerland'),
(145, 'Syria'),
(146, 'Taiwan'),
(147, 'Tanzania'),
(148, 'Thailand'),
(149, 'Togo'),
(150, 'Tunisia'),
(151, 'Turkey'),
(152, 'Ukraine'),
(153, 'United Arab Emirates'),
(154, 'United Kingdom'),
(155, 'United States of America'),
(156, 'Uruguay'),
(157, 'Venezuela'),
(158, 'Vietnam'),
(159, 'Yemen'),
(160, 'Zambia');

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`id`, `email`, `password`, `name`, `gender`, `image`, `created_at`, `verified`) VALUES
(1, 'wassim@gmail.com', '$2y$10$LwdVX2b/JI8gR1GBsJ/tT..za.KL2WBN.jLhVsOfXEvFpGmLTAMmK', 'wassim ben jdida', 'male', '1551882351_avatar.png', '2018-12-31 20:35:21', 0),
(2, 'leila@gmail.com', '$2y$10$wAfXM3TTtArC3neLzZbnE.nednDOv5cI31PEcAeiuFlxoGAopARB6', 'leila zanina', 'female', '', '2019-01-24 08:11:28', 0),
(3, 'test@gmail.com', '$2y$10$Sk4pHEOsicuZY.w57MYZv.dAqh7eyNgAnJ6BFYeHUtgYfGA7FncRu', 'test test', 'male', '', '2019-02-05 12:29:06', 0),
(4, 'asma@gmail.com', '$2y$10$eaEkuQD1Pc5uFR7Mgd9L8ezyLQWOZ6t/7oL.yRPsnJoDFRxAeBtxi', 'asma jdida', 'female', '', '2019-02-05 12:31:31', 0),
(5, 'aymen@gmail.com', '$2y$10$9vJjezCcTlA0mtK7w7zCyeAR7LlUpuxTXaZp8SO9TMy/jn.vuasry', 'aymen test', 'male', '', '2019-02-10 20:36:51', 0);

-- --------------------------------------------------------

--
-- Structure de la table `discount`
--

CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `precent` int(11) NOT NULL,
  `discount_to` int(11) NOT NULL,
  `expire_in` bigint(255) NOT NULL,
  `expired` int(11) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `discount`
--

INSERT INTO `discount` (`id`, `amount`, `precent`, `discount_to`, `expire_in`, `expired`, `created_at`) VALUES
(30, 0, 35, 1549794727, 1554847200, 0, '2019-02-10');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `order_items` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `order_by`, `order_items`, `amount`, `payment_method`, `status`, `created_at`) VALUES
(3, 1, '[\"9\"]', '25.00', 'Credit card', 'shipped', '2019-02-26 19:22:23'),
(4, 1, '[\"15\"]', '85.00', 'paypal', 'pending', '2019-03-08 10:32:35'),
(5, 1, '[\"16\"]', '50.00', 'Credit card', 'pending', '2019-03-08 10:33:01'),
(6, 1, '[\"17\"]', '50.00', 'Credit card', 'pending', '2019-03-08 10:45:21');

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `method` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bywho` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `status`, `method`, `created_at`, `bywho`, `amount`, `currency`, `order_id`) VALUES
('ch_1E8AZSDl19Hbi39Z4DbdsJTl', 'succeeded', 'credit card', '2019-02-26 19:22:23', 1, '25.00', 'usd', 3),
('ch_1EBf4gDl19Hbi39ZsqWrNKfO', 'succeeded', 'credit card', '2019-03-08 10:33:01', 1, '50.00', 'usd', 5),
('ch_1EBfGcDl19Hbi39Zy0q6XuCm', 'succeeded', 'credit card', '2019-03-08 10:45:21', 1, '50.00', 'usd', 6),
('PAYID-LSBDNEQ1DL118655V359823X', 'approved', 'paypal', '2019-03-08 10:32:35', 1, '85.00', 'USD', 4);

-- --------------------------------------------------------

--
-- Structure de la table `pimages`
--

CREATE TABLE `pimages` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `connector` int(30) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pimages`
--

INSERT INTO `pimages` (`id`, `image`, `connector`) VALUES
(45, '1547226108_about-02.jpg', 1547226108),
(47, '1551544272_Womens_Short_Sleeve_-_Single_Black.jpg', 1547226108),
(48, '1547226209_product-01.jpg', 1547226209),
(49, '1547226209_product-02.jpg', 1547226209),
(50, '1547226209_product-13.jpg', 1547226209),
(51, '1551955535_watch (6).jpg', 1547226259),
(52, '1551955535_watch (1).jpg', 1547226259),
(53, '1551955535_watch (2).jpg', 1547226259),
(54, '1547307982_2300kcal diet.png', 1547307982),
(55, '1547307982_2500.PNG', 1547307982),
(56, '1547307982_2800kcal diet.png', 1547307982),
(57, '1547322007_25f236f4bd564449c7a0b6abf197b90e.jpg', 1547322007),
(58, '1547322007_49696366_601622413610027_3491986498171961344_n.png', 1547322007),
(60, '1547322064_banner-06.jpg', 1547322064),
(61, '1547322064_banner-08.jpg', 1547322064),
(62, '1547322065_gallery-01.jpg', 1547322064),
(63, '1547322124_gallery-06.jpg', 1547322124),
(64, '1547322124_product-07.jpg', 1547322124),
(65, '1547322124_product-08.jpg', 1547322124),
(66, '1547322192_product-04.jpg', 1547322191),
(67, '1547322192_product-detail-03.jpg', 1547322191),
(68, '1547322192_slide-05.jpg', 1547322191),
(69, '1548075073_gallery-08.jpg', 1548075072),
(70, '1548075073_product-03.jpg', 1548075072),
(71, '1548075073_product-09.jpg', 1548075072),
(72, '1548075073_product-11.jpg', 1548075072),
(73, '1548075166_gallery-08.jpg', 1548075166),
(74, '1548075166_product-03.jpg', 1548075166),
(75, '1548075166_product-09.jpg', 1548075166),
(76, '1548075166_product-11.jpg', 1548075166),
(77, '1548075199_gallery-08.jpg', 1548075199),
(78, '1548075199_product-03.jpg', 1548075199),
(79, '1548075199_product-09.jpg', 1548075199),
(80, '1548075199_product-11.jpg', 1548075199),
(81, '1548075220_gallery-08.jpg', 1548075220),
(82, '1548075220_product-03.jpg', 1548075220),
(83, '1548075220_product-09.jpg', 1548075220),
(84, '1548075220_product-11.jpg', 1548075220),
(85, '1548075291_gallery-08.jpg', 1548075291),
(86, '1548075291_product-03.jpg', 1548075291),
(87, '1548075292_product-09.jpg', 1548075291),
(88, '1548075292_product-11.jpg', 1548075291),
(89, '1548075295_gallery-08.jpg', 1548075295),
(90, '1548075295_product-03.jpg', 1548075295),
(91, '1548075295_product-09.jpg', 1548075295),
(92, '1548075295_product-11.jpg', 1548075295),
(93, '1548075301_gallery-08.jpg', 1548075301),
(94, '1548075301_product-03.jpg', 1548075301),
(95, '1548075301_product-09.jpg', 1548075301),
(96, '1548075301_product-11.jpg', 1548075301),
(97, '1548075841_banner-03.jpg', 1548075841),
(98, '1548075841_banner-09.jpg', 1548075841),
(99, '1548075841_product-12.jpg', 1548075841),
(100, '1548077435_banner-03.jpg', 1548077434),
(101, '1548077435_banner-09.jpg', 1548077434),
(102, '1548077435_product-12.jpg', 1548077434),
(103, '1551955567_watch (3).jpg', 1548157302),
(104, '1551955567_watch (4).jpg', 1548157302),
(105, '1551955567_watch (5).jpg', 1548157302),
(106, '1551549524_a45f8c1072a6787ef370d372a0b8d845.jpg', 1548157376),
(107, '1551549524_newbalance-996gl-madeinusa3.jpg', 1548157376),
(108, '1548425570_mockup5.jpg', 1548425570),
(109, '1548425570_mockup6.jpg', 1548425570),
(110, '1548425570_mockup7.jpg', 1548425570),
(111, '1551550291_photo-1507457379470-08b800bebc67.jpg', 1548792371),
(112, '1548792372_51xKKagqWRL.jpg', 1548792371),
(113, '1548792372_ktqg_vault_111_rival_mouse.jpg', 1548792371),
(114, '1551550291_pexels-photo-788946.jpeg', 1548792371),
(115, '1551551819_light-men-s-dress-shirt_925x.jpg', 1549050327),
(116, '1551551819_man-rolls-up-shirt-sleeves_925x.jpg', 1549050327),
(117, '1551551819_back-of-mens-white-shirt_925x.jpg', 1549050327),
(118, '1549050433_antony-morato-original-47853.jpg', 1549050433),
(119, '1549050433_images.jpg', 1549050433),
(120, '1549050433_s10_1519891003.jpg', 1549050433),
(121, '1549050470_images (3).jpg', 1549050470),
(122, '1549050470_tÃ©lÃ©chargÃ© (1).jpg', 1549050470),
(123, '1549050470_tÃ©lÃ©chargÃ©.jpg', 1549050470),
(124, '1551544546_41BS8Sg5NQL._SL1024_.jpg', 1549812094),
(125, '1551550786_1193592897_01.g_1200-w-st_g.jpg', 1549812094),
(126, '1551544546_JB-by-Jerome-Boateng-JBF107-1-d07.jpg', 1549812094),
(127, '1551550103_photo-1550524037-99dfde7409a6.jpg', 1549812094),
(128, '1550339345_baby-Girl-suit-trolls-2017-new-boys-childen-girls-clothing-set-Children-T-shirt-jeans-cartoon.jpg', 1550339345),
(129, '1550339345_Girls-Summer-Clothes-Kids-Boutique-Outfits-Sleeveless-Polka-Dot-Blouse-Pink-Short-Pants-Clothes-Set-2.jpg', 1550339345),
(131, '1550339345_one-stop-shopping-for-fabulous-kids-clothes-lands-end_fvhnyp.jpeg', 1550339345),
(132, '1551549973_77f326566bb2bb0b112d600120136844.jpg', 1551549973),
(133, '1551549973_c875b47dc6e5e919276c66504b2dea99.jpg', 1551549973),
(134, '1551549973_ed25253c5fa01eba5379e44e2970d219.jpg', 1551549973),
(135, '1551549973_newbalance-996gl-madeinusa4.jpg', 1551549973),
(136, '1551551020_3788f701-784d-4940-9625-96669b711427.jpg', 1551551020),
(137, '1551551020_17921a18-209c-44e9-af1a-03a7bd8524ff.jpg', 1551551020),
(138, '1551551020_ca0ae5fc-e002-49c8-8375-476e731b62a5.jpg', 1551551020),
(139, '1551551020_d925c1de-f7df-41fb-9e71-cacb780f5902.jpg', 1551551020);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `old_price` decimal(15,2) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `materials` varchar(255) NOT NULL,
  `image` int(30) UNSIGNED NOT NULL,
  `size` varchar(500) NOT NULL,
  `color` varchar(500) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `sub_category` varchar(255) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `old_price`, `description`, `materials`, `image`, `size`, `color`, `quantity`, `category`, `sub_category`, `discount_id`, `created_at`) VALUES
(15, 'woman tshirt', '22.50', '0.00', '  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vestibulum, nisi vel ultricies tincidunt, nibh nulla sagittis mi, eget rhoncus elit eros sit amet ligula. Cras consequat scelerisque mi quis egestas. Etiam rutrum placerat felis consectetur dignissim. Morbi at velit feugiat nibh porta varius. Vivamus malesuada sit amet metus vitae tristique. Nulla tempor vulputate rhoncus. Duis eu tempor quam, eu interdum dolor. Praesent in quam vel                        ', '60% coutton', 1547226108, '[\"S\",\"M\",\"L\",\"4XL\"]', '[\"black\",\"yellow\",\"purple\",\"brown\"]', 15, 'women', '', 0, '2019-01-11 18:01:48'),
(16, 'white tshirt', '33.80', '52.00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vestibulum, nisi vel ultricies tincidunt, nibh nulla sagittis mi, eget rhoncus elit eros sit amet ligula. Cras consequat scelerisque mi quis egestas. Etiam rutrum placerat felis consectetur dignissim. Morbi at velit feugiat nibh porta varius. Vivamus malesuada sit amet metus vitae tristique. Nulla tempor vulputate rhoncus. Duis eu tempor quam, eu interdum dolor. Praesent in quam vel ', '60% cotton', 1547226209, '[\"M\",\"L\",\"XL\"]', '[\"white\",\"red\",\"yellow\"]', 66, 'women', '', 1549794727, '2019-01-11 18:03:29'),
(17, 'Audemars Piguet Royal Oak Watch', '18.75', '0.00', '           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vestibulum, nisi vel ultricies tincidunt, nibh nulla sagittis mi, eget rhoncus elit eros sit amet ligula. Cras consequat scelerisque mi quis egestas. Etiam rutrum placerat felis consectetur dignissim. Morbi at velit feugiat nibh porta varius. Vivamus malesuada sit amet metus vitae tristique. Nulla tempor vulputate rhoncus. Duis eu tempor quam, eu interdum dolor. Praesent in quam vel            ', 'Broadcloth', 1547226259, '[\"M\",\"L\",\"XL\"]', '[\"black\",\"white\",\"brown\"]', 50, 'accessoires', '', 0, '2019-01-11 18:04:19'),
(20, 'Back pack', '22.00', '0.00', '     . Phasellus consectetur tempor euismod. Aenean id nisl imperdiet, vehicula ex sed, molestie nisl. Aenean bibendum lacus accumsan magna eleifend, vel efficitur massa tincidunt. Phasellus scelerisque erat vitae urna porta scelerisque. Cras molestie placerat nunc, a scelerisque tortor laoreet sed. Proin vitae elementum leo, quis dignissim enim. Proin vestibulum rhoncus diam, sit amet auctor augue porttitor eget. Quisque hendrer     ', 'Broadcloth', 1547322064, '[\"without size\"]', '[\"black\",\"white\",\"orange\"]', 30, 'accessoires', '', 0, '2019-01-12 20:41:04'),
(21, 'Jacket for woman', '48.75', '75.00', '. Phasellus consectetur tempor euismod. Aenean id nisl imperdiet, vehicula ex sed, molestie nisl. Aenean bibendum lacus accumsan magna eleifend, vel efficitur massa tincidunt. Phasellus scelerisque erat vitae urna porta scelerisque. Cras molestie placerat nunc, a scelerisque tortor laoreet sed. Proin vitae elementum leo, quis dignissim enim. Proin vestibulum rhoncus diam, sit amet auctor augue porttitor eget. Quisque hendrer', '25% coutton', 1547322124, '[\"S\",\"M\",\"XXXL\",\"4XL\"]', '[\"grey\",\"black\",\"white\",\"blue\"]', 20, 'women', '', 1549794727, '2019-01-12 20:42:04'),
(22, 'coat for laides', '26.25', '0.00', '. Phasellus consectetur tempor euismod. Aenean id nisl imperdiet, vehicula ex sed, molestie nisl. Aenean bibendum lacus accumsan magna eleifend, vel efficitur massa tincidunt. Phasellus scelerisque erat vitae urna porta scelerisque. Cras molestie placerat nunc, a scelerisque tortor laoreet sed. Proin vitae elementum leo, quis dignissim enim. Proin vestibulum rhoncus diam, sit amet auctor augue porttitor eget. Quisque hendrer', '60% coutton', 1547322191, '[\"L\",\"XL\",\"4XL\"]', '[\"black\",\"white\",\"brown\"]', 20, 'women', '', 0, '2019-01-12 20:43:11'),
(23, 'test product too', '40.00', '0.00', 'ddddddddddddddddddddddddddddddddddddddddd', 'Broadcloth', 1548075072, '[\"M\",\"L\",\"XL\"]', '[\"green\",\"orange\",\"brown\"]', 50, 'accessoires', '', 0, '2019-01-21 13:51:12'),
(31, 'Some accessoires', '29.25', '0.00', '  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ac ex eu enim ullamcorper tempor. Phasellus quis tempor diam, ut hendrerit augue. Integer ultrices blandit consectetur. Pellentesque luctus dui nunc, non interdum libero volutpat vitae. Proin suscipit et tortor tempus hendrerit. Etiam iaculis nullementum mollis dapibus. Etiam lacus leo, posuere ac libero eu, consectetur dapibus ipsum.  ', 'Broadcloth', 1548077434, '[\"without size\"]', '[\"white\",\"red\"]', 21, 'accessoires', '', 0, '2019-01-21 14:30:34'),
(32, 'IWC Pilot', '38.00', '0.00', '       n sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielle       ', '60% coutton', 1548157302, '[\"without size\"]', '[\"black\",\"white\"]', 20, 'accessoires', 'wtaches', 0, '2019-01-22 12:41:42'),
(33, 'New Balance Shoes for Marathon (Men and Women)', '39.00', '60.00', '    n sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielle    ', '60% cotton', 1548157376, '[\"M\",\"XL\"]', '[\"grey\",\"black\",\"blue\"]', 520, 'accessoires', 'shoes', 1549794727, '2019-01-22 12:42:56'),
(35, 'Electronic tests', '27.00', '0.00', '  elit. In at molestie justo, sed finibus magna. Praesent vitae ex ut libero faucibus mollis quis id orci. Sed non ante nec purus vestibulum consectetur sed id elit. Phasellus in fringilla felis. Nullam accumsan dapibus felis ut elementum. Donec erat ex, mollis et fringilla at, egestas sed dui. Integer gravida tortor sed vulputate lobortis. Duis et hendrerit est. Maecenas porttitor odio in sodales ullamcorper. Curabitur gravida tellus arcu, non condimentum erat ullamcorper u  ', 'plastic, iron', 1548792371, '[\"without size\"]', '[\"black\",\"blue\",\"yellow\"]', 30, 'electronics', 'mouse', 0, '2019-01-29 21:06:11'),
(36, 'tshirt for test', '68.25', '105.00', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean fringilla egestas euismod. Suspendisse vitae quam molestie, condimentum risus at, interdum orci. Nullam tempus erat quis aliquet vestibulum. Aenean condimentum et ligula vitae condimentum. Nam nunc justo, maximus eu molestie a, elementum semper tortor. Vivamus aliquet mi in iaculis egestas. In laoreet, velit et auctor sodales, tortor risus cursus mi, non finibus dui purus at odio.  ', 'Broadcloth', 1549050327, '[\"M\",\"XXXL\",\"4XL\"]', '[\"grey\",\"white\",\"red\",\"purple\"]', 50, 'men', 't-shirts', 1549794727, '2019-02-01 20:45:27'),
(37, 'put your product title here', '78.00', '120.00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean fringilla egestas euismod. Suspendisse vitae quam molestie, condimentum risus at, interdum orci. Nullam tempus erat quis aliquet vestibulum. Aenean condimentum et ligula vitae condimentum. Nam nunc justo, maximus eu molestie a, elementum semper tortor. Vivamus aliquet mi in iaculis egestas. In laoreet, velit et auctor sodales, tortor risus cursus mi, non finibus dui purus at odio. ', '25% coutton', 1549050433, '[\"S\",\"M\",\"L\"]', '[\"black\",\"white\"]', 10, 'men', 't-shirts', 1549794727, '2019-02-01 20:47:13'),
(38, 'Eyewear', '25.00', '0.00', '  Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vehicula finibus porttitor. Duis suscipit metus vitae dui dapibus, a euismod dui euismod. Integer eget lacus eget sapien tincidunt vestibulum ut sed urna. Vestibulum pretium arcu at dui ultrices fringilla. Ut placerat lacus sit amet nisl faucibus, id finibus velit tincidunt. Duis quis neque tincidunt, sollicitudin tortor non, placerat metus. Vestibulum lacinia dictum arcu non facilisis. Aliquam ante leo, dapibus a augue ac, venenatis elementum neque. In vitae nunc at nunc vulputate posuere sit amet et justo. Suspendisse porta est sit amet turpis cursus, nec ornare odio malesuada. Nam non porttitor sem. Pellentesque hendrerit pharetra tristique. Etiam quis leo mauris.    ', 'plastic', 1549812094, '[\"without size\"]', '[\"grey\",\"brown\"]', 50, 'accessoires', 'glasses', 0, '2019-02-10 16:21:34'),
(39, 'baby Girl T-shirt jeans cartoon', '50.00', '0.00', '  Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vehicula finibus porttitor. Duis suscipit metus vitae dui dapibus, a euismod dui euismod. Integer eget lacus eget sapien tincidunt vestibulum ut sed urna. Vestibulum pretium arcu at dui ultrices fringilla. Ut placerat lacus sit amet nisl faucibus, id finibus velit tincidunt. Duis quis neque tincidunt, sollicitudin tortor non, placerat metus. Vestibulum lacinia dictum arcu non facilisis. Aliquam ante leo, dapibus a augue ac, venenatis elementum neque. In vitae nunc at nunc vulputate posuere sit amet et justo. Suspe  ', '60% coutton', 1550339345, '[\"S\",\"M\"]', '[\"black\",\"red\"]', 50, 'kids', 't-shirts', 0, '2019-02-16 18:49:05'),
(40, 'new balance 996gl made in usa', '120.00', '0.00', '  Explicabo nihil dicta aut unde ducimus quis repellendus omnis. Doloremque neque consequatur vel voluptas qui dolore. Nihil tempora consequatur aut ea. Nihil qui consequatur reprehenderit hic voluptas.\r\n \r\nLaudantium sed repudiandae minus. Autem sunt nesciunt quaerat voluptatem in porro vel velit. Laborum consequuntur vitae cupiditate nulla deserunt mollitia odio voluptas in. Amet labore quasi odit.\r\nIste autem atque expedita est nam. Iure rerum vitae occaecati qui repellendus ut expedita voluptatem. Aut dolores possimus alias tenetur numquam. Impedit non quae.  ', 'fabric', 1551549973, '[\"S\",\"M\"]', '[\"white\",\"blue\",\"red\",\"yellow\"]', 50, 'men', 'shoes', 0, '2019-03-02 19:06:13'),
(41, 'GENTLE MONSTER GM sun glasses', '85.00', '0.00', ' Aut dolor perspiciatis quas. Qui qui et ab mollitia ut aliquid expedita eligendi. Et ipsum sunt est perspiciatis ad cum iste illum perspiciatis. Rerum voluptatem aut.\r\nIpsa voluptatem molestias in quis saepe. Non placeat eos unde. Voluptatem est quos eos sequi est suscipit beatae. Vero similique temporibus temporibus et animi. Consequatur perspiciatis ratione quod ipsam laboriosam ullam. Ipsam corrupti quos quia ea.\r\n \r\nNulla aut ab illum quo qui perspiciatis vero excepturi maiores. Delectus voluptatem aut corrupti accusantium aspernatur. Aliquam accusantium et iste repudiandae voluptates corporis totam. Quis commodi nobis et sed expedita quos ut quos architecto. Necessitatibus laborum repellendus id. ', 'plastic', 1551551020, '[\"without size\"]', '[\"black\",\"yellow\",\"orange\"]', 20, 'accessoires', 'sun glasses', 0, '2019-03-02 19:23:40');

-- --------------------------------------------------------

--
-- Structure de la table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `ship_by` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shipping`
--

INSERT INTO `shipping` (`id`, `ship_by`, `firstname`, `lastname`, `address`, `city`, `state`, `country`, `zip`) VALUES
(3, 1, 'wassim', 'ben jdida', 'test address', 'california', 'los angelos', 'United States of America', 4000),
(4, 1, 'wassim', 'ben jdida', 'test address', 'california', 'los angelos', 'United States of America', 4000),
(5, 1, 'wassim', 'ben jdida', 'test address', 'california', 'los angelos', 'United States of America', 4000),
(6, 1, 'wassim', 'ben jdida', 'test address', 'california', 'los angelos', 'United States of America', 4000);

-- --------------------------------------------------------

--
-- Structure de la table `shop_front`
--

CREATE TABLE `shop_front` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `about` varchar(5000) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `insta` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `map` mediumtext NOT NULL,
  `paypal` varchar(500) NOT NULL,
  `stripe` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shop_front`
--

INSERT INTO `shop_front` (`id`, `logo`, `favicon`, `about`, `facebook`, `twitter`, `insta`, `address`, `phone`, `email`, `map`, `paypal`, `stripe`) VALUES
(1, '1551881968_logo.png', '1549618831_logo-2.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consequat consequat enim, non auctor massa ultrices non. Morbi sed odio massa. Quisque at vehicula tellus, sed tincidunt augue. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas varius egestas diam, eu sodales metus scelerisque congue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas gravida justo eu arcu egestas convallis. Nullam eu erat bibendum, tempus ipsum eget, dictum enim. Donec non neque ut enim dapibus tincidunt vitae nec augue. Suspendisse potenti. Proin ut est diam. Donec condimentum euismod tortor, eget facilisis diam faucibus et. Morbi a tempor elit.\r\n\r\nDonec gravida lorem elit, quis condimentum ex semper sit amet. Fusce eget ligula magna. Aliquam aliquam imperdiet sodales. Ut fringilla turpis in vehicula vehicula. Pellentesque congue ac orci ut gravida. Aliquam erat volutpat. Donec iaculis lectus a arcu facilisis, eu sodales lectus sagittis. Etiam pellentesque, magna vel dictum rutrum, neque justo eleifend elit, vel tincidunt erat arcu ut sem. Sed rutrum, turpis ut commodo efficitur, quam velit convallis ipsum, et maximus enim ligula ac ligula.', '#fb_link', '#twitter_link', '#instagram_link', 'Shoop Center 8th floor, 379 Hudson St, New York, NY 10018 US', '+1 800 1236879', 'support@shoop.com', '{\"loc\":\"London, UK\",\"lng\":\"-0.12775829999998223\",\"lat\":\"51.5073509\",\"key\":\"AIzaSyD0xe-UM20NvkmGJGRI6SdpCS0AhopMHo0\",\"service\":\"paid\"}', '{\"id\":\"AbXWrYU-icict8PAyx4bsdPX9l151T3T2EsKbod6-GXlZdLBG7rmlwqh3tWZE1_9F9vE5RORgfS3oN9F\",\"secret\":\"EIiD9UbDWQG0WEQRd1qfu4dh_Tcw0FsWaP3CUzQK2BUpUYoMkVXmRpM88jUAmZArheyxNBuk4PFxKFIt\"}', '{\"id\":\"pk_test_LJVgwzqnoPk9aQ3SNOR2Yxf4\",\"secret\":\"sk_test_6IXodzmA0cBGo4sbnEbf5bBQ\"}');

-- --------------------------------------------------------

--
-- Structure de la table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `appear` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sub_category`
--

INSERT INTO `sub_category` (`id`, `name`, `parent`, `appear`, `created_at`) VALUES
(2, 'shoes', 1, 1, '2019-01-22 11:40:27'),
(3, 't-shirts', 1, 1, '2019-01-22 11:40:27'),
(4, 'jackets', 1, 1, '2019-01-22 11:40:27'),
(5, 'shoes', 3, 1, '2019-01-22 11:45:49'),
(6, 'wtaches', 3, 1, '2019-01-22 11:45:49'),
(7, 'pc', 4, 1, '2019-01-29 21:00:52'),
(8, 'mouse', 4, 1, '2019-01-29 21:00:52'),
(9, 'keyboard', 4, 1, '2019-01-29 21:00:52'),
(12, 'glasses', 3, 1, '2019-02-10 16:20:23'),
(13, 'chaines', 3, 1, '2019-02-10 16:20:23'),
(14, 'glasses', 2, 1, '2019-02-15 19:16:00'),
(15, 'shoes', 2, 1, '2019-02-15 19:16:00'),
(16, 'shirts', 2, 1, '2019-02-15 19:16:00'),
(17, 'jeans', 2, 1, '2019-02-15 19:16:00'),
(18, 'cats', 6, 1, '2019-02-15 19:17:47'),
(19, 'dogs', 6, 1, '2019-02-15 19:17:47'),
(20, 'chiwawa', 6, 1, '2019-02-15 19:17:47'),
(21, 'jeans', 7, 1, '2019-02-16 18:46:11'),
(22, 't-shirts', 7, 1, '2019-02-16 18:46:11'),
(23, 'shoes', 7, 1, '2019-02-16 18:46:11'),
(24, 'sun glasses', 1, 1, '2019-03-02 19:22:17');

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `wish_to` int(11) NOT NULL,
  `wish_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`id`, `wish_to`, `wish_by`, `created_at`) VALUES
(38, 35, 5, '2019-02-10 20:39:44'),
(44, 37, 2, '2019-02-16 19:17:01'),
(48, 32, 1, '2019-03-02 17:41:04'),
(51, 40, 1, '2019-03-06 15:21:26');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products` (`cart_to`),
  ADD KEY `user` (`cart_by`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countryID`);

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order` (`order_by`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_by` (`bywho`),
  ADD KEY `order_id` (`order_id`);

--
-- Index pour la table `pimages`
--
ALTER TABLE `pimages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_to_who` (`ship_by`);

--
-- Index pour la table `shop_front`
--
ALTER TABLE `shop_front`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usb_cate` (`parent`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wish_to` (`wish_to`),
  ADD KEY `wish_by_whom` (`wish_by`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `countryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `pimages`
--
ALTER TABLE `pimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `shop_front`
--
ALTER TABLE `shop_front`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `products` FOREIGN KEY (`cart_to`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user` FOREIGN KEY (`cart_by`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order` FOREIGN KEY (`order_by`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_by` FOREIGN KEY (`bywho`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `shipping_to_who` FOREIGN KEY (`ship_by`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `usb_cate` FOREIGN KEY (`parent`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wish_by_whom` FOREIGN KEY (`wish_by`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wish_to` FOREIGN KEY (`wish_to`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
