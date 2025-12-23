-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 23 déc. 2025 à 02:44
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dungeon_explorer`
--

-- --------------------------------------------------------

--
-- Structure de la table `chapter`
--

CREATE TABLE `chapter` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT 'Chapitre',
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapter`
--

INSERT INTO `chapter` (`id`, `title`, `content`, `image`) VALUES
(1, 'Chapitre 1 : Introduction', 'Le ciel est lourd ce soir sur le village du Val Perdu... La quête commence.', 'sprites/background/taverne1.png'),
(2, 'Chapitre 2 : L\'orée de la forêt', 'Vous franchissez la lisière des arbres... Deux chemins s\'offrent à vous.', 'sprites/background/foret3.png'),
(3, 'Chapitre 3 : L\'arbre aux corbeaux', 'Votre choix vous mène devant un vieux chêne... Un bruit de pas feutres se fait entendre.', 'sprites/background/foret4.png'),
(4, 'Chapitre 4 : Le sanglier enragé', 'En progressant, le calme est brisé par un grognement... Un énorme sanglier surgit.', 'sprites/background/foret2.png'),
(5, 'Chapitre 5 : Rencontre avec le paysan', 'Vous tombez sur un vieux paysan... Il vous met en garde contre les créatures.', 'sprites/background/foret2.png'),
(6, 'Chapitre 6 : Le loup noir', 'Un loup noir surgit devant vous... Le combat est inévitable.', 'sprites/background/foret2.png'),
(7, 'Chapitre 7 : La clairière aux pierres anciennes', 'Vous atteignez une clairière étrange, entourée de pierres dressées...', 'sprites/background/clairierePierre.png'),
(8, 'Chapitre 8 : Les murmures du ruisseau', 'Vous arrivez près d\'un ruisseau... Des murmures étranges émanent de la rive.', 'sprites/background/clairierePierre2.png'),
(9, 'Chapitre 9 : Au pied du château', 'La forêt se disperse... Le château en ruines projette une ombre menaçante.', 'sprites/background/ruinesChateau.png'),
(10, 'Chapitre 10 : La lumière au bout du néant', 'Le monde se dérobe sous vos pieds... Une lueur douce apparaît au loin.', 'sprites/background/mort1.png'),
(11, 'Chapitre 11 : La curiosité tua le chat', 'Qu\'avez-vous fait, Malheureux ! Rendez-vous sans perdre de temps au chapitre 10.', 'sprites/background/clairierePierre2.png'),
(12, 'Chapitre 12 : La Grande Porte', 'Vous poussez les lourdes portes du château. Le hall est immense et poussiéreux. Deux escaliers se présentent : l\'un monte vers les tours, l\'autre descend dans les ténèbres.', './sprites/background/hall1.png'),
(13, 'Chapitre 13 : Les Cachots Humides', 'L\'air est rance. Vous entendez des cliquetis d\'os. C\'est une prison oubliée.', './sprites/background/prison1.png'),
(14, 'Chapitre 14 : Une Rencontre Ossue', 'Un tas d\'ossements se reconstitue devant vous ! Un squelette garde le passage.', './sprites/background/prison1.png'),
(15, 'Chapitre 15 : La Cellule d\'Aldric', 'Après le combat, vous entendez une voix faible venant d\'une cellule. Un homme y est enchaîné.', './sprites/background/cellule1.png'),
(16, 'Chapitre 16 : L\'Armurerie Royale', 'Vous avez choisi de monter. Vous arrivez dans une ancienne armurerie. La plupart des armes sont rouillées, mais un coffre semble intact.', './sprites/background/armurerie1.png'),
(17, 'Chapitre 17 : Le Couloir des Portraits', 'Les yeux des portraits semblent vous suivre. Une sensation de froid intense vous envahit.', './sprites/background/couloir1.png'),
(18, 'Chapitre 18 : Le Gardien Spirituel', 'Un spectre surgit d\'un tableau ! Il ne vous laissera pas passer.', './sprites/background/couloir1.png'),
(19, 'Chapitre 19 : La Salle du Trône', 'Vous y êtes. Une immense salle éclairée par des torches bleues. Au fond, une silhouette en armure noire monte la garde devant une porte magique.', './sprites/background/chevalierNoir.png'),
(20, 'Chapitre 20 : Le Duel du Chevalier', 'Le Chevalier Noir dégaine son épée sans un mot. C\'est un duel à mort.', './sprites/background/chevalierNoir.png'),
(21, 'Chapitre 21 : L\'Antre du Sorcier', 'Derrière le trône se cachait un laboratoire secret. Le Seigneur Nécromancien vous attendait. \"Tu as fait du chemin pour mourir ici\", ricane-t-il.', './sprites/background/laboratoire1.png'),
(22, 'Chapitre 22 : Le Rituel Interrompu', 'Le Nécromancien invoque sa puissance maudite !', './sprites/background/laboratoire1.png'),
(23, 'Chapitre 23 : Victoire Éclatante', 'Le sorcier s\'effondre en poussière. Le château commence à trembler. Vous devez fuir !', './sprites/background/fuite1.png'),
(24, 'Chapitre 24 : La Fuite', 'Vous courez vers la sortie alors que les pierres tombent autour de vous.', './sprites/background/fuite2.png'),
(25, 'Chapitre 25 : Épilogue', 'Vous regardez le château s\'effondrer depuis la colline. Le Val Perdu est enfin sauvé. Vous êtes un héros.', './sprites/background/fin1.png');

-- --------------------------------------------------------

--
-- Structure de la table `chapter_treasure`
--

CREATE TABLE `chapter_treasure` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapter_treasure`
--

INSERT INTO `chapter_treasure` (`id`, `chapter_id`, `item_id`, `quantity`) VALUES
(1, 12, 1, 50),
(2, 13, 4, 1),
(3, 15, 7, 1),
(4, 16, 8, 1),
(5, 16, 2, 2),
(6, 23, 1, 1000);

-- --------------------------------------------------------

--
-- Structure de la table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `base_pv` int(11) NOT NULL,
  `base_mana` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `initiative` int(11) NOT NULL,
  `max_items` int(11) NOT NULL,
  `image` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `class`
--

INSERT INTO `class` (`id`, `name`, `description`, `base_pv`, `base_mana`, `strength`, `initiative`, `max_items`) VALUES
(1, 'Guerrier', 'Un combattant qui veut prouver sa valeur en combattant des ennemis toujours plus forts.', 120, 30, 15, 5, 3),
(2, 'Magicien', 'Un puissant mage qui se repose sur les nombreux sorts qu\'il connait.', 100, 120, 10, 6, 5);

-- --------------------------------------------------------

--
-- Structure de la table `encounter`
--

CREATE TABLE `encounter` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `monster_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `encounter`
--

INSERT INTO `encounter` (`id`, `chapter_id`, `monster_id`) VALUES
(1, 6, 1),
(2, 4, 2),
(3, 14, 4),
(4, 18, 5),
(5, 20, 6),
(6, 22, 7);

-- --------------------------------------------------------

--
-- Structure de la table `hero`
--

CREATE TABLE `hero` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `biography` text DEFAULT NULL,
  `pv` int(11) NOT NULL,
  `mana` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `initiative` int(11) NOT NULL,
  `armor_item_id` int(11) DEFAULT NULL,
  `primary_weapon_item_id` int(11) DEFAULT NULL,
  `secondary_weapon_item_id` int(11) DEFAULT NULL,
  `shield_item_id` int(11) DEFAULT NULL,
  `xp` int(11) NOT NULL,
  `current_level` int(11) DEFAULT 1,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `hero`
--

INSERT INTO `hero` (`id`, `name`, `class_id`, `image`, `biography`, `pv`, `mana`, `strength`, `initiative`, `armor_item_id`, `primary_weapon_item_id`, `secondary_weapon_item_id`, `shield_item_id`, `xp`, `current_level`) VALUES
(1, 'Geralt', 1, 'sprites\\joueur\\guerrierMale\\doubleHache\\guerrierMaleDoubleHache1.png', NULL, 120, 30, 15, 5, NULL, NULL, NULL, NULL, 0, 1),
(2, 'Gandalf', 2, 'sprites/joueur/mageMale/mageMale1.png', NULL, 131, 160, 30, 42, NULL, NULL, NULL, NULL, 4820, 9);

-- --------------------------------------------------------

--
-- Structure de la table `hero_progress`
--

CREATE TABLE `hero_progress` (
  `hero_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `hero_progress`
--

INSERT INTO `hero_progress` (`hero_id`, `chapter_id`) VALUES
(1, 1),
(2, 25);

-- --------------------------------------------------------

--
-- Structure de la table `hero_spell`
--

CREATE TABLE `hero_spell` (
  `id` int(11) NOT NULL,
  `hero_id` int(11) NOT NULL,
  `spell_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `hero_spell`
--

INSERT INTO `hero_spell` (`id`, `hero_id`, `spell_id`) VALUES
(1, 2, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inventory`
--

INSERT INTO `inventory` (`id`, `hero_id`, `item_id`, `quantity`) VALUES
(1, 1, 1, 40),
(2, 2, 1, 70),
(3, 2, 3, 2),
(4, 2, 5, 3),
(5, 2, 9, 3);

-- --------------------------------------------------------

--
-- Structure de la table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `item_type` varchar(50) NOT NULL,
  `value` int(3) NOT NULL,
  `bonus` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `item_type`, `value`, `bonus`) VALUES
(1, 'Pièces', 'Monnaie courante à utiliser dans les villes.', 'Monnaie', 0, 0),
(2, 'Potion de Soin', 'Restaure 50 PV.', 'Potion', 10, 50),
(3, 'Potion de Mana', 'Restaure 30 Mana.', 'Potion', 15, 30),
(4, 'Épée rouillée', 'Une vieille lame, mieux que rien.', 'Arme', 5, 3),
(5, 'Épée du Chevalier', 'Une lame forgée pour la garde royale.', 'Arme', 50, 12),
(6, 'Bâton des Arcanes', 'Vibre d\'une énergie magique.', 'Arme', 60, 15),
(7, 'Clé du Donjon', 'Une lourde clé en fer noir.', 'Clé', 0, 0),
(8, 'Plastron en Acier', 'Offre une protection solide.', 'Armure', 80, 10),
(9, 'Amulette de Vie', 'Un bijou ancien.', 'Trésor', 200, 0);

-- --------------------------------------------------------

--
-- Structure de la table `level`
--

CREATE TABLE `level` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `required_xp` int(11) NOT NULL,
  `pv_bonus` int(11) NOT NULL,
  `mana_bonus` int(11) NOT NULL,
  `strength_bonus` int(11) NOT NULL,
  `initiative_bonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `level`
--

INSERT INTO `level` (`id`, `class_id`, `level`, `required_xp`, `pv_bonus`, `mana_bonus`, `strength_bonus`, `initiative_bonus`) VALUES
(1, 1, 2, 200, 5, 0, 2, 4),
(2, 1, 3, 500, 5, 0, 2, 5),
(3, 1, 4, 900, 5, 0, 2, 6),
(4, 1, 5, 1400, 4, 0, 2, 5),
(5, 1, 6, 2000, 6, 0, 2, 7),
(6, 1, 7, 2700, 5, 0, 2, 4),
(7, 1, 8, 3500, 4, 0, 2, 6),
(8, 1, 9, 4400, 6, 0, 2, 5),
(9, 1, 10, 5400, 5, 0, 2, 7),
(10, 1, 11, 6500, 4, 0, 2, 4),
(11, 1, 12, 7700, 6, 0, 2, 6),
(12, 1, 13, 9000, 5, 0, 2, 5),
(13, 1, 14, 10400, 4, 0, 2, 7),
(14, 1, 15, 11900, 5, 0, 2, 4),
(15, 1, 16, 13500, 6, 0, 2, 6),
(16, 1, 17, 15200, 4, 0, 2, 5),
(17, 1, 18, 17000, 5, 0, 2, 7),
(18, 1, 19, 18900, 6, 0, 2, 6),
(19, 1, 20, 20900, 4, 0, 2, 4),
(20, 1, 21, 23000, 5, 0, 2, 5),
(21, 1, 22, 25200, 6, 0, 2, 7),
(22, 1, 23, 27500, 4, 0, 2, 6),
(23, 1, 24, 29900, 5, 0, 2, 4),
(24, 1, 25, 32400, 6, 0, 2, 5),
(25, 1, 26, 35000, 4, 0, 2, 7),
(26, 1, 27, 37700, 5, 0, 2, 6),
(27, 1, 28, 40500, 6, 0, 2, 4),
(28, 1, 29, 43400, 4, 0, 2, 5),
(29, 1, 30, 46400, 5, 0, 2, 7),
(30, 1, 31, 49500, 6, 0, 2, 6),
(31, 1, 32, 52700, 4, 0, 2, 4),
(32, 1, 33, 56000, 5, 0, 2, 5),
(33, 1, 34, 59400, 6, 0, 2, 7),
(34, 1, 35, 62900, 4, 0, 2, 6),
(35, 1, 36, 66500, 5, 0, 2, 4),
(36, 1, 37, 70200, 6, 0, 2, 5),
(37, 1, 38, 74000, 4, 0, 2, 7),
(38, 1, 39, 77900, 5, 0, 2, 6),
(39, 1, 40, 81900, 6, 0, 2, 4),
(40, 1, 41, 86000, 4, 0, 2, 5),
(41, 1, 42, 90200, 5, 0, 2, 7),
(42, 1, 43, 94500, 6, 0, 2, 6),
(43, 1, 44, 98900, 4, 0, 2, 4),
(44, 1, 45, 103400, 5, 0, 2, 5),
(45, 1, 46, 108000, 6, 0, 2, 7),
(46, 1, 47, 112700, 4, 0, 2, 6),
(47, 1, 48, 117500, 5, 0, 2, 4),
(48, 1, 49, 122400, 6, 0, 2, 5),
(49, 1, 50, 127400, 4, 0, 2, 7),
(50, 1, 51, 132500, 5, 0, 2, 6),
(51, 1, 52, 137700, 6, 0, 2, 4),
(52, 1, 53, 143000, 4, 0, 2, 5),
(53, 1, 54, 148400, 5, 0, 2, 7),
(54, 1, 55, 153900, 6, 0, 2, 6),
(55, 1, 56, 159500, 4, 0, 2, 4),
(56, 1, 57, 165200, 5, 0, 2, 5),
(57, 1, 58, 171000, 6, 0, 2, 7),
(58, 1, 59, 176900, 4, 0, 2, 6),
(59, 1, 60, 182900, 5, 0, 2, 4),
(60, 1, 61, 189000, 6, 0, 2, 5),
(61, 1, 62, 195200, 4, 0, 2, 7),
(62, 1, 63, 201500, 5, 0, 2, 6),
(63, 1, 64, 207900, 6, 0, 2, 4),
(64, 1, 65, 214400, 4, 0, 2, 5),
(65, 1, 66, 221000, 5, 0, 2, 7),
(66, 1, 67, 227700, 6, 0, 2, 6),
(67, 1, 68, 234500, 4, 0, 2, 4),
(68, 1, 69, 241400, 5, 0, 2, 5),
(69, 1, 70, 248400, 6, 0, 2, 7),
(70, 1, 71, 255500, 4, 0, 2, 6),
(71, 1, 72, 262700, 5, 0, 2, 4),
(72, 1, 73, 270000, 6, 0, 2, 5),
(73, 1, 74, 277400, 4, 0, 2, 7),
(74, 1, 75, 284900, 5, 0, 2, 6),
(75, 1, 76, 292500, 6, 0, 2, 4),
(76, 1, 77, 300200, 4, 0, 2, 5),
(77, 1, 78, 308000, 5, 0, 2, 7),
(78, 1, 79, 315900, 6, 0, 2, 6),
(79, 1, 80, 323900, 4, 0, 2, 4),
(80, 1, 81, 332000, 5, 0, 2, 5),
(81, 1, 82, 340200, 6, 0, 2, 7),
(82, 1, 83, 348500, 4, 0, 2, 6),
(83, 1, 84, 356900, 5, 0, 2, 4),
(84, 1, 85, 365400, 6, 0, 2, 5),
(85, 1, 86, 374000, 4, 0, 2, 7),
(86, 1, 87, 382700, 5, 0, 2, 6),
(87, 1, 88, 391500, 6, 0, 2, 4),
(88, 1, 89, 400400, 4, 0, 2, 5),
(89, 1, 90, 409400, 5, 0, 2, 7),
(90, 1, 91, 418500, 6, 0, 2, 6),
(91, 1, 92, 427700, 4, 0, 2, 4),
(92, 1, 93, 437000, 5, 0, 2, 5),
(93, 1, 94, 446400, 6, 0, 2, 7),
(94, 1, 95, 455900, 4, 0, 2, 6),
(95, 1, 96, 465500, 5, 0, 2, 4),
(96, 1, 97, 475200, 6, 0, 2, 5),
(97, 1, 98, 485000, 4, 0, 2, 7),
(98, 1, 99, 494900, 5, 0, 2, 6),
(99, 1, 100, 504900, 6, 0, 2, 4),
(100, 2, 2, 200, 3, 5, 2, 5),
(101, 2, 3, 500, 4, 5, 3, 3),
(102, 2, 4, 900, 4, 5, 3, 4),
(103, 2, 5, 1400, 4, 5, 2, 6),
(104, 2, 6, 2000, 5, 5, 2, 6),
(105, 2, 7, 2700, 5, 5, 3, 4),
(106, 2, 8, 3500, 3, 5, 3, 3),
(107, 2, 9, 4400, 3, 5, 2, 6),
(108, 2, 10, 5400, 3, 5, 3, 3),
(109, 2, 11, 6500, 5, 5, 3, 3),
(110, 2, 12, 7700, 4, 5, 1, 5),
(111, 2, 13, 9000, 3, 5, 1, 3),
(112, 2, 14, 10400, 4, 5, 1, 3),
(113, 2, 15, 11900, 5, 5, 2, 6),
(114, 2, 16, 13500, 3, 5, 3, 6),
(115, 2, 17, 15200, 4, 5, 1, 5),
(116, 2, 18, 17000, 4, 5, 3, 5),
(117, 2, 19, 18900, 3, 5, 2, 6),
(118, 2, 20, 20900, 5, 5, 3, 5),
(119, 2, 21, 23000, 4, 5, 1, 6),
(120, 2, 22, 25200, 4, 5, 2, 4),
(121, 2, 23, 27500, 3, 5, 3, 6),
(122, 2, 24, 29900, 5, 5, 3, 3),
(123, 2, 25, 32400, 3, 5, 1, 6),
(124, 2, 26, 35000, 5, 5, 1, 3),
(125, 2, 27, 37700, 3, 5, 2, 6),
(126, 2, 28, 40500, 5, 5, 1, 3),
(127, 2, 29, 43400, 4, 5, 3, 4),
(128, 2, 30, 46400, 5, 5, 1, 4),
(129, 2, 31, 49500, 3, 5, 2, 4),
(130, 2, 32, 52700, 5, 5, 2, 4),
(131, 2, 33, 56000, 4, 5, 3, 5),
(132, 2, 34, 59400, 4, 5, 1, 3),
(133, 2, 35, 62900, 4, 5, 1, 6),
(134, 2, 36, 66500, 3, 5, 1, 3),
(135, 2, 37, 70200, 3, 5, 2, 3),
(136, 2, 38, 74000, 4, 5, 1, 3),
(137, 2, 39, 77900, 4, 5, 3, 6),
(138, 2, 40, 81900, 4, 5, 3, 6),
(139, 2, 41, 86000, 5, 5, 1, 3),
(140, 2, 42, 90200, 4, 5, 1, 5),
(141, 2, 43, 94500, 4, 5, 1, 6),
(142, 2, 44, 98900, 4, 5, 1, 6),
(143, 2, 45, 103400, 4, 5, 3, 5),
(144, 2, 46, 108000, 3, 5, 3, 4),
(145, 2, 47, 112700, 4, 5, 3, 6),
(146, 2, 48, 117500, 5, 5, 3, 5),
(147, 2, 49, 122400, 5, 5, 1, 6),
(148, 2, 50, 127400, 4, 5, 2, 5),
(149, 2, 51, 132500, 3, 5, 1, 5),
(150, 2, 52, 137700, 4, 5, 1, 3),
(151, 2, 53, 143000, 5, 5, 2, 3),
(152, 2, 54, 148400, 5, 5, 1, 3),
(153, 2, 55, 153900, 5, 5, 2, 6),
(154, 2, 56, 159500, 5, 5, 3, 6),
(155, 2, 57, 165200, 5, 5, 1, 5),
(156, 2, 58, 171000, 3, 5, 2, 5),
(157, 2, 59, 176900, 5, 5, 1, 4),
(158, 2, 60, 182900, 4, 5, 3, 6),
(159, 2, 61, 189000, 5, 5, 3, 3),
(160, 2, 62, 195200, 5, 5, 3, 6),
(161, 2, 63, 201500, 4, 5, 2, 4),
(162, 2, 64, 207900, 4, 5, 1, 3),
(163, 2, 65, 214400, 4, 5, 1, 5),
(164, 2, 66, 221000, 3, 5, 1, 6),
(165, 2, 67, 227700, 5, 5, 3, 3),
(166, 2, 68, 234500, 5, 5, 1, 6),
(167, 2, 69, 241400, 4, 5, 2, 4),
(168, 2, 70, 248400, 3, 5, 1, 5),
(169, 2, 71, 255500, 3, 5, 1, 5),
(170, 2, 72, 262700, 4, 5, 3, 5),
(171, 2, 73, 270000, 3, 5, 2, 6),
(172, 2, 74, 277400, 4, 5, 1, 6),
(173, 2, 75, 284900, 5, 5, 2, 5),
(174, 2, 76, 292500, 5, 5, 3, 5),
(175, 2, 77, 300200, 4, 5, 2, 5),
(176, 2, 78, 308000, 4, 5, 3, 6),
(177, 2, 79, 315900, 5, 5, 1, 3),
(178, 2, 80, 323900, 4, 5, 3, 6),
(179, 2, 81, 332000, 3, 5, 2, 6),
(180, 2, 82, 340200, 5, 5, 2, 3),
(181, 2, 83, 348500, 3, 5, 3, 4),
(182, 2, 84, 356900, 4, 5, 2, 5),
(183, 2, 85, 365400, 4, 5, 1, 4),
(184, 2, 86, 374000, 5, 5, 3, 4),
(185, 2, 87, 382700, 5, 5, 2, 6),
(186, 2, 88, 391500, 5, 5, 3, 3),
(187, 2, 89, 400400, 5, 5, 2, 4),
(188, 2, 90, 409400, 3, 5, 2, 4),
(189, 2, 91, 418500, 4, 5, 2, 4),
(190, 2, 92, 427700, 4, 5, 3, 4),
(191, 2, 93, 437000, 4, 5, 1, 3),
(192, 2, 94, 446400, 5, 5, 3, 6),
(193, 2, 95, 455900, 5, 5, 1, 3),
(194, 2, 96, 465500, 5, 5, 3, 6),
(195, 2, 97, 475200, 3, 5, 1, 4),
(196, 2, 98, 485000, 3, 5, 2, 5),
(197, 2, 99, 494900, 4, 5, 2, 4),
(198, 2, 100, 504900, 4, 5, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `next_chapter_id` int(11) DEFAULT NULL,
  `choice_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `links`
--

INSERT INTO `links` (`id`, `chapter_id`, `next_chapter_id`, `choice_text`) VALUES
(1, 1, 2, 'Accepter la quête et entrer dans la forêt'),
(2, 2, 3, 'Emprunter le chemin sinueux'),
(3, 2, 4, 'Choisir le sentier couvert de ronces'),
(4, 3, 5, 'Rester prudent'),
(5, 3, 6, 'Ignorer les bruits et poursuivre'),
(6, 4, 8, 'Vaincre le sanglier'),
(7, 4, 10, 'Être terrassé par le sanglier'),
(8, 5, 7, 'Continuer après avoir écouté le paysan'),
(9, 6, 7, 'Survivre au loup'),
(10, 6, 10, 'Être terrassé par le loup'),
(11, 7, 8, 'S\'approcher des pierres'),
(12, 7, 9, 'Continuer à travers la forêt'),
(13, 8, 11, 'Toucher la pierre gravée'),
(14, 8, 9, 'Ignorer la curiosité et poursuivre'),
(15, 10, 1, 'Reprendre l\'aventure depuis le début'),
(16, 11, 10, 'Retour forcé au néant'),
(17, 9, 12, 'Entrer dans le château'),
(18, 12, 16, 'Monter vers l\'étage'),
(19, 12, 13, 'Descendre vers les cachots'),
(20, 13, 14, 'Avancer dans l\'obscurité'),
(21, 14, 15, 'Fouiller les cellules'),
(22, 14, 10, 'Mourir sous les coups du squelette'),
(23, 15, 19, 'Utiliser le passage secret révélé par Aldric'),
(24, 15, 12, 'Remonter au Hall principal'),
(25, 16, 17, 'Prendre l\'équipement et continuer'),
(26, 17, 18, 'Affronter l\'apparition'),
(27, 18, 19, 'Entrer dans la salle du trône'),
(28, 18, 10, 'Votre âme est absorbée par le spectre'),
(29, 19, 20, 'Défier le Chevalier Noir'),
(30, 20, 21, 'Ouvrir la porte magique'),
(31, 20, 10, 'Le Chevalier Noir vous transperce'),
(32, 21, 22, 'Mettre fin à sa folie'),
(33, 22, 23, 'Le terrasser une bonne fois pour toutes'),
(34, 22, 10, 'Le Nécromancien prend votre âme'),
(35, 23, 24, 'Courir !'),
(36, 24, 25, 'Admirer son oeuvre');

-- --------------------------------------------------------

--
-- Structure de la table `marchand`
--

CREATE TABLE `marchand` (
  `pnj_id` int(11) NOT NULL,
  `shop_name` varchar(100) DEFAULT NULL,
  `currency` varchar(50) DEFAULT 'gold'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `marchand_inventory`
--

CREATE TABLE `marchand_inventory` (
  `id` int(11) NOT NULL,
  `marchand_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `monster`
--

CREATE TABLE `monster` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `pv` int(11) NOT NULL,
  `mana` int(11) DEFAULT NULL,
  `initiative` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `attack` text DEFAULT NULL,
  `xp` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `monster`
--

INSERT INTO `monster` (`id`, `name`, `pv`, `mana`, `initiative`, `strength`, `attack`, `xp`, `image`) VALUES
(1, 'Loup sauvage', 100, 10, 4, 10, 'Le loup sauvage vous mord', 100, './sprites/monstres/trashMob/loup/loup1.png'),
(2, 'Sanglier enragé', 150, 0, 6, 6, 'Le sanglier vous charge à toute vitesse', 120, './sprites/monstres/trashMob/sanglier/sanglier1.png'),
(3, 'Orc', 200, 50, 100, 30, 'L\'orc vous met un puissant coup de massue.', 500, './sprites/monstres/trashMob/orc/orc1.png'),
(4, 'Squelette Guerrier', 80, 0, 3, 12, 'Le squelette frappe avec ses os.', 80, './sprites/monstres/trashMob/squelette/squeletteGuerrier/squeletteGuerrier1.png'),
(5, 'Garde Fantôme', 120, 20, 6, 15, 'Le fantôme traverse votre armure.', 150, './sprites/monstres/trashMob/guerrierFantome.png'),
(6, 'Chevalier Noir', 250, 0, 5, 25, 'Le chevalier abat sa lourde épée.', 300, './sprites/monstres/boss/chevalierNoir.png'),
(7, 'Seigneur Nécromancien', 400, 200, 8, 20, 'Le nécromancien lance un sort de mort.', 1000, './sprites/monstres/boss/necromancien.png');

-- --------------------------------------------------------

--
-- Structure de la table `monster_loot`
--

CREATE TABLE `monster_loot` (
  `id` int(11) NOT NULL,
  `monster_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `drop_rate` decimal(5,2) DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `monster_loot`
--

INSERT INTO `monster_loot` (`id`, `monster_id`, `item_id`, `quantity`, `drop_rate`) VALUES
(1, 1, 1, 10, 50.00),
(2, 4, 1, 5, 30.00),
(3, 5, 3, 1, 25.00),
(4, 6, 5, 1, 10.00),
(5, 7, 9, 1, 100.00);

-- --------------------------------------------------------

--
-- Structure de la table `pnj`
--

CREATE TABLE `pnj` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pnj`
--

INSERT INTO `pnj` (`id`, `name`, `description`, `image`) VALUES
(1, 'Aldric le Captif', 'Un ancien aventurier emprisonné ici depuis des lustres.', './sprites/pnj/prisonnier.png');

-- --------------------------------------------------------

--
-- Structure de la table `pnj_dialogue`
--

CREATE TABLE `pnj_dialogue` (
  `id` int(11) NOT NULL,
  `pnj_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT 0,
  `dialogue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pnj_dialogue`
--

INSERT INTO `pnj_dialogue` (`id`, `pnj_id`, `sequence`, `dialogue`) VALUES
(1, 1, 0, 'Par les dieux ! Un vivant ?'),
(2, 1, 1, 'Méfiez-vous du Chevalier Noir à l\'étage, son armure est impénétrable par la magie.'),
(3, 1, 2, 'Prenez ceci, je l\'avais caché dans ma botte...');

-- --------------------------------------------------------

--
-- Structure de la table `spell`
--

CREATE TABLE `spell` (
  `id` int(11) NOT NULL,
  `mana_cost` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  `spell_name` varchar(64) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `spell`
--

INSERT INTO `spell` (`id`, `mana_cost`, `damage`, `spell_name`, `description`) VALUES
(1, 60, 150, 'Boule de feu', 'Une énorme boule de feu carbonisant votre adversaire.'),
(2, 5, 30, 'Lame magique', 'Vous projetez votre mana pour créer une lame qui fend les airs... et vos adversaires.');

-- --------------------------------------------------------

--
-- Structure de la table `users_aria`
--

CREATE TABLE `users_aria` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=Aria DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chapter_treasure`
--
ALTER TABLE `chapter_treasure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chapter_id` (`chapter_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `encounter`
--
ALTER TABLE `encounter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `monster_id` (`monster_id`);

--
-- Index pour la table `hero`
--
ALTER TABLE `hero`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `armor_item_id` (`armor_item_id`),
  ADD KEY `primary_weapon_item_id` (`primary_weapon_item_id`),
  ADD KEY `secondary_weapon_item_id` (`secondary_weapon_item_id`),
  ADD KEY `shield_item_id` (`shield_item_id`);

--
-- Index pour la table `hero_progress`
--
ALTER TABLE `hero_progress`
ADD PRIMARY KEY (`hero_id`, `chapter_id`),
ADD CONSTRAINT `fk_hero_progress_hero`
    FOREIGN KEY (`hero_id`) REFERENCES `Hero`(`id`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_hero_progress_chapter`
    FOREIGN KEY (`chapter_id`) REFERENCES `Chapter`(`id`);


--
-- Index pour la table `hero_spell`
--
ALTER TABLE `hero_spell`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hero_id` (`hero_id`,`spell_id`),
  ADD KEY `spell_id` (`spell_id`);

--
-- Index pour la table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hero_id` (`hero_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Index pour la table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `next_chapter_id` (`next_chapter_id`);

--
-- Index pour la table `marchand`
--
ALTER TABLE `marchand`
  ADD PRIMARY KEY (`pnj_id`);

--
-- Index pour la table `marchand_inventory`
--
ALTER TABLE `marchand_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marchand_id` (`marchand_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `monster`
--
ALTER TABLE `monster`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `monster_loot`
--
ALTER TABLE `monster_loot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `monster_id` (`monster_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `pnj`
--
ALTER TABLE `pnj`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `pnj_dialogue`
--
ALTER TABLE `pnj_dialogue`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pnj_id` (`pnj_id`,`sequence`);

--
-- Index pour la table `spell`
--
ALTER TABLE `spell`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_aria`
--
ALTER TABLE `users_aria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `chapter_treasure`
--
ALTER TABLE `chapter_treasure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `encounter`
--
ALTER TABLE `encounter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `hero`
--
ALTER TABLE `hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `hero_spell`
--
ALTER TABLE `hero_spell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `level`
--
ALTER TABLE `level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT pour la table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `marchand_inventory`
--
ALTER TABLE `marchand_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `monster`
--
ALTER TABLE `monster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `monster_loot`
--
ALTER TABLE `monster_loot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `pnj`
--
ALTER TABLE `pnj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `pnj_dialogue`
--
ALTER TABLE `pnj_dialogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `spell`
--
ALTER TABLE `spell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users_aria`
--
ALTER TABLE `users_aria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chapter_treasure`
--
ALTER TABLE `chapter_treasure`
  ADD CONSTRAINT `chapter_treasure_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `chapter_treasure_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Contraintes pour la table `encounter`
--
ALTER TABLE `encounter`
  ADD CONSTRAINT `encounter_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `encounter_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `monster` (`id`);

--
-- Contraintes pour la table `hero`
--
ALTER TABLE `hero`
  ADD CONSTRAINT `hero_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `hero_ibfk_2` FOREIGN KEY (`armor_item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `hero_ibfk_3` FOREIGN KEY (`primary_weapon_item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `hero_ibfk_4` FOREIGN KEY (`secondary_weapon_item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `hero_ibfk_5` FOREIGN KEY (`shield_item_id`) REFERENCES `items` (`id`);

--
-- Contraintes pour la table `hero_progress`
--
ALTER TABLE `hero_progress`
  ADD CONSTRAINT `hero_progress_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`),
  ADD CONSTRAINT `hero_progress_ibfk_2` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`);

--
-- Contraintes pour la table `hero_spell`
--
ALTER TABLE `hero_spell`
  ADD CONSTRAINT `hero_spell_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`),
  ADD CONSTRAINT `hero_spell_ibfk_2` FOREIGN KEY (`spell_id`) REFERENCES `spell` (`id`);

--
-- Contraintes pour la table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Contraintes pour la table `level`
--
ALTER TABLE `level`
  ADD CONSTRAINT `level_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`);

--
-- Contraintes pour la table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `links_ibfk_2` FOREIGN KEY (`next_chapter_id`) REFERENCES `chapter` (`id`);

--
-- Contraintes pour la table `marchand`
--
ALTER TABLE `marchand`
  ADD CONSTRAINT `marchand_ibfk_1` FOREIGN KEY (`pnj_id`) REFERENCES `pnj` (`id`);

--
-- Contraintes pour la table `marchand_inventory`
--
ALTER TABLE `marchand_inventory`
  ADD CONSTRAINT `marchand_inventory_ibfk_1` FOREIGN KEY (`marchand_id`) REFERENCES `marchand` (`pnj_id`),
  ADD CONSTRAINT `marchand_inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Contraintes pour la table `monster_loot`
--
ALTER TABLE `monster_loot`
  ADD CONSTRAINT `monster_loot_ibfk_1` FOREIGN KEY (`monster_id`) REFERENCES `monster` (`id`),
  ADD CONSTRAINT `monster_loot_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Contraintes pour la table `pnj_dialogue`
--
ALTER TABLE `pnj_dialogue`
  ADD CONSTRAINT `pnj_dialogue_ibfk_1` FOREIGN KEY (`pnj_id`) REFERENCES `pnj` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
