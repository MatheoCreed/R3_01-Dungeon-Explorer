-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 25, 2025 at 06:40 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Hero_Progress;
DROP TABLE IF EXISTS Inventory;
DROP TABLE IF EXISTS Links;
DROP TABLE IF EXISTS Encounter;
DROP TABLE IF EXISTS Chapter_Treasure;
DROP TABLE IF EXISTS Marchand_Inventory;
DROP TABLE IF EXISTS Marchand;
DROP TABLE IF EXISTS PNJ_Dialogue;
DROP TABLE IF EXISTS PNJ;
DROP TABLE IF EXISTS Level;
DROP TABLE IF EXISTS Hero;
DROP TABLE IF EXISTS Monster_Loot;
DROP TABLE IF EXISTS Monster;
DROP TABLE IF EXISTS Chapter;
DROP TABLE IF EXISTS Items;
DROP TABLE IF EXISTS Class;
DROP TABLE IF EXISTS users_aria;

-- On réactive la vérification pour la création (sécurité)
SET FOREIGN_KEY_CHECKS = 1;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dungeon_explorer`
--

-- --------------------------------------------------------

--
-- Table structure for table `chapter`
--

CREATE TABLE `chapter` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Chapitre',
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapter`
--

INSERT INTO `chapter` (`id`, `title`, `content`, `image`) VALUES
(1, 'Chapitre 1 : Introduction', 'Le ciel est lourd ce soir sur le village du Val Perdu... La quête commence.', '/R3_01-Dungeon-Explorer/sprites/background/taverne1.png'),
(2, 'Chapitre 2 : L\'orée de la forêt', 'Vous franchissez la lisière des arbres... Deux chemins s\'offrent à vous.', '/R3_01-Dungeon-Explorer/sprites/background/foret3.png'),
(3, 'Chapitre 3 : L\'arbre aux corbeaux', 'Votre choix vous mène devant un vieux chêne... Un bruit de pas feutres se fait entendre.', '/R3_01-Dungeon-Explorer/sprites/background/foret4.png'),
(4, 'Chapitre 4 : Le sanglier enragé', 'En progressant, le calme est brisé par un grognement... Un énorme sanglier surgit.', '/R3_01-Dungeon-Explorer/sprites/background/foret2.png'),
(5, 'Chapitre 5 : Rencontre avec le paysan', 'Vous tombez sur un vieux paysan... Il vous met en garde contre les créatures.', '/R3_01-Dungeon-Explorer/sprites/background/foret2.png'),
(6, 'Chapitre 6 : Le loup noir', 'Un loup noir surgit devant vous... Le combat est inévitable.', '/R3_01-Dungeon-Explorer/sprites/background/foret2.png'),
(7, 'Chapitre 7 : La clairière aux pierres anciennes', 'Vous atteignez une clairière étrange, entourée de pierres dressées...', '/R3_01-Dungeon-Explorer/sprites/background/clairierePierre.png'),
(8, 'Chapitre 8 : Les murmures du ruisseau', 'Vous arrivez près d\'un ruisseau... Des murmures étranges émanent de la rive.', '/R3_01-Dungeon-Explorer/sprites/background/clairierePierre2.png'),
(9, 'Chapitre 9 : Au pied du château', 'La forêt se disperse... Le château en ruines projette une ombre menaçante.', '/R3_01-Dungeon-Explorer/sprites/background/ruinesChateau.png'),
(10, 'Chapitre 10 : La lumière au bout du néant', 'Le monde se dérobe sous vos pieds... Une lueur douce apparaît au loin.', '/R3_01-Dungeon-Explorer/sprites/background/mort1.png'),
(11, 'Chapitre 11 : La curiosité tua le chat', 'Qu\'avez-vous fait, Malheureux ! Rendez-vous sans perdre de temps au chapitre 10.', '/R3_01-Dungeon-Explorer/sprites/background/clairierePierre2.png'),
(12, 'Chapitre 12 : La Grande Porte', 'Vous poussez les lourdes portes du château. Le hall est immense et poussiéreux. Deux escaliers se présentent : l\'un monte vers les tours, l\'autre descend dans les ténèbres.', '/R3_01-Dungeon-Explorer/sprites/background/hall1.png'),
(13, 'Chapitre 13 : Les Cachots Humides', 'L\'air est rance. Vous entendez des cliquetis d\'os. C\'est une prison oubliée.', '/R3_01-Dungeon-Explorer/sprites/background/prison1.png'),
(14, 'Chapitre 14 : Une Rencontre Ossue', 'Un tas d\'ossements se reconstitue devant vous ! Un squelette garde le passage.', '/R3_01-Dungeon-Explorer/sprites/background/prison1.png'),
(15, 'Chapitre 15 : La Cellule d\'Aldric', 'Après le combat, vous entendez une voix faible venant d\'une cellule. Un homme y est enchaîné.', '/R3_01-Dungeon-Explorer/sprites/background/cellule1.png'),
(16, 'Chapitre 16 : L\'Armurerie Royale', 'Vous avez choisi de monter. Vous arrivez dans une ancienne armurerie. La plupart des armes sont rouillées, mais un coffre semble intact.', '/R3_01-Dungeon-Explorer/sprites/background/armurerie1.png'),
(17, 'Chapitre 17 : Le Couloir des Portraits', 'Les yeux des portraits semblent vous suivre. Une sensation de froid intense vous envahit.', '/R3_01-Dungeon-Explorer/sprites/background/couloir1.png'),
(18, 'Chapitre 18 : Le Gardien Spirituel', 'Un spectre surgit d\'un tableau ! Il ne vous laissera pas passer.', '/R3_01-Dungeon-Explorer/sprites/background/couloir1.png'),
(19, 'Chapitre 19 : La Salle du Trône', 'Vous y êtes. Une immense salle éclairée par des torches bleues. Au fond, une silhouette en armure noire monte la garde devant une porte magique.', '/R3_01-Dungeon-Explorer/sprites/background/chevalierNoir.png'),
(20, 'Chapitre 20 : Le Duel du Chevalier', 'Le Chevalier Noir dégaine son épée sans un mot. C\'est un duel à mort.', '/R3_01-Dungeon-Explorer/sprites/background/chevalierNoir.png'),
(21, 'Chapitre 21 : L\'Antre du Sorcier', 'Derrière le trône se cachait un laboratoire secret. Le Seigneur Nécromancien vous attendait. \"Tu as fait du chemin pour mourir ici\", ricane-t-il.', '/R3_01-Dungeon-Explorer/sprites/background/laboratoire1.png'),
(22, 'Chapitre 22 : Le Rituel Interrompu', 'Le Nécromancien invoque sa puissance maudite !', '/R3_01-Dungeon-Explorer/sprites/background/laboratoire1.png'),
(23, 'Chapitre 23 : Victoire Éclatante', 'Le sorcier s\'effondre en poussière. Le château commence à trembler. Vous devez fuir !', '/R3_01-Dungeon-Explorer/sprites/background/fuite1.png'),
(24, 'Chapitre 24 : La Fuite', 'Vous courez vers la sortie alors que les pierres tombent autour de vous.', '/R3_01-Dungeon-Explorer/sprites/background/fuite2.png'),
(25, 'Chapitre 25 : Épilogue', 'Vous regardez le château s\'effondrer depuis la colline. Le Val Perdu est enfin sauvé. Vous êtes un héros.', '/R3_01-Dungeon-Explorer/sprites/background/fin1.png');

-- --------------------------------------------------------

--
-- Table structure for table `chapter_treasure`
--

CREATE TABLE `chapter_treasure` (
  `id` int NOT NULL,
  `chapter_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapter_treasure`
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
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `base_pv` int NOT NULL,
  `base_mana` int NOT NULL,
  `strength` int NOT NULL,
  `initiative` int NOT NULL,
  `max_items` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `description`, `base_pv`, `base_mana`, `strength`, `initiative`, `max_items`, `image`) VALUES
(1, 'Guerrier', 'Un combattant qui veut prouver sa valeur en combattant des ennemis toujours plus forts.', 120, 30, 15, 5, 3, '/R3_01-Dungeon-Explorer/sprites/joueur/guerrierMaleEpeeBouclierFace1_694ac0ccb1865.png'),
(2, 'Magicien', 'Un puissant mage qui se repose sur les nombreux sorts qu\'il connait.', 100, 120, 10, 6, 5, '/R3_01-Dungeon-Explorer/sprites/joueur/mageMaleFace1_694ac00910f8f.png'),
(3, 'Voleur', 'Un combattant agile spécialisé dans la vitesse et les attaques précises.', 160, 10, 12, 10, 4, '/R3_01-Dungeon-Explorer/sprites/joueur/voleurFace1_694ac0408981a.png');

-- --------------------------------------------------------

--
-- Table structure for table `encounter`
--

CREATE TABLE `encounter` (
  `id` int NOT NULL,
  `chapter_id` int DEFAULT NULL,
  `monster_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encounter`
--

INSERT INTO `encounter` (`id`, `chapter_id`, `monster_id`) VALUES
(1, 6, 1),
(2, 4, 2),
(3, 14, 4),
(4, 18, 5),
(5, 20, 6),
(7, 22, 7);

-- --------------------------------------------------------

--
-- Table structure for table `hero`
--

CREATE TABLE `hero` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `class_id` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `biography` text COLLATE utf8mb4_general_ci,
  `pv` int NOT NULL,
  `mana` int NOT NULL,
  `strength` int NOT NULL,
  `initiative` int NOT NULL,
  `armor_item_id` int DEFAULT NULL,
  `primary_weapon_item_id` int DEFAULT NULL,
  `secondary_weapon_item_id` int DEFAULT NULL,
  `shield_item_id` int DEFAULT NULL,
  `xp` int NOT NULL,
  `current_level` int DEFAULT '1',
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero`
--

INSERT INTO `hero` (`id`, `name`, `class_id`, `image`, `biography`, `pv`, `mana`, `strength`, `initiative`, `armor_item_id`, `primary_weapon_item_id`, `secondary_weapon_item_id`, `shield_item_id`, `xp`, `current_level`, `user_id`) VALUES
(1, 'Geralt', 1, '/R3_01-Dungeon-Explorer/sprites/joueur/guerrierMale/doubleHache/guerrierMaleDoubleHache1.png', NULL, 120, 30, 15, 5, NULL, NULL, NULL, NULL, 0, 1, 0),
(2, 'Gandalf', 2, '/R3_01-Dungeon-Explorer/sprites/joueur/mageMale/mageMale1.png', NULL, 131, 160, 30, 42, NULL, NULL, NULL, NULL, 4820, 9, 0),
(23, 'xx', 1, '/R3_01-Dungeon-Explorer/sprites/joueur/guerrierMaleEpeeBouclierFace1_694ac0ccb1865.png', NULL, 120, 30, 15, 5, NULL, NULL, NULL, NULL, 100, 1, 1),
(24, 'Le magicien d\'oz', 2, '/R3_01-Dungeon-Explorer/sprites/joueur/mageMaleFace1_694ac00910f8f.png', NULL, 100, 120, 10, 6, NULL, NULL, NULL, NULL, 120, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `hero_progress`
--

CREATE TABLE `hero_progress` (
  `hero_id` int NOT NULL,
  `chapter_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_progress`
--

INSERT INTO `hero_progress` (`hero_id`, `chapter_id`) VALUES
(1, 1),
(2, 4),
(24, 5),
(23, 7);

-- --------------------------------------------------------

--
-- Table structure for table `hero_spell`
--

CREATE TABLE `hero_spell` (
  `id` int NOT NULL,
  `hero_id` int NOT NULL,
  `spell_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_spell`
--

INSERT INTO `hero_spell` (`id`, `hero_id`, `spell_id`) VALUES
(1, 2, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int NOT NULL,
  `hero_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `hero_id`, `item_id`, `quantity`) VALUES
(1, 1, 1, 40),
(2, 2, 1, 70),
(3, 2, 3, 2),
(13, 23, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `item_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `value` int NOT NULL,
  `bonus` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
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
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id` int NOT NULL,
  `class_id` int DEFAULT NULL,
  `level` int NOT NULL,
  `required_xp` int NOT NULL,
  `pv_bonus` int NOT NULL,
  `mana_bonus` int NOT NULL,
  `strength_bonus` int NOT NULL,
  `initiative_bonus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level`
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
(198, 2, 100, 504900, 4, 5, 3, 3),
(209, 3, 2, 200, 5, 0, 2, 4),
(210, 3, 3, 500, 5, 0, 2, 5),
(211, 3, 4, 900, 5, 0, 2, 6),
(212, 3, 5, 1400, 4, 0, 2, 5),
(213, 3, 6, 2000, 6, 0, 2, 7),
(214, 3, 7, 2700, 5, 0, 2, 4),
(215, 3, 8, 3500, 4, 0, 2, 6),
(216, 3, 9, 4400, 6, 0, 2, 5),
(217, 3, 10, 5400, 5, 0, 2, 7),
(218, 3, 11, 6500, 4, 0, 2, 4),
(219, 3, 12, 7700, 6, 0, 2, 6),
(220, 3, 13, 9000, 5, 0, 2, 5),
(221, 3, 14, 10400, 4, 0, 2, 7),
(222, 3, 15, 11900, 5, 0, 2, 4),
(223, 3, 16, 13500, 6, 0, 2, 6),
(224, 3, 17, 15200, 4, 0, 2, 5),
(225, 3, 18, 17000, 5, 0, 2, 7),
(226, 3, 19, 18900, 6, 0, 2, 6),
(227, 3, 20, 20900, 4, 0, 2, 4),
(228, 3, 21, 23000, 5, 0, 2, 5),
(229, 3, 22, 25200, 6, 0, 2, 7),
(230, 3, 23, 27500, 4, 0, 2, 6),
(231, 3, 24, 29900, 5, 0, 2, 4),
(232, 3, 25, 32400, 6, 0, 2, 5),
(233, 3, 26, 35000, 4, 0, 2, 7),
(234, 3, 27, 37700, 5, 0, 2, 6),
(235, 3, 28, 40500, 6, 0, 2, 4),
(236, 3, 29, 43400, 4, 0, 2, 5),
(237, 3, 30, 46400, 5, 0, 2, 7),
(238, 3, 31, 49500, 6, 0, 2, 6),
(239, 3, 32, 52700, 4, 0, 2, 4),
(240, 3, 33, 56000, 5, 0, 2, 5),
(241, 3, 34, 59400, 6, 0, 2, 7),
(242, 3, 35, 62900, 4, 0, 2, 6),
(243, 3, 36, 66500, 5, 0, 2, 4),
(244, 3, 37, 70200, 6, 0, 2, 5),
(245, 3, 38, 74000, 4, 0, 2, 7),
(246, 3, 39, 77900, 5, 0, 2, 6),
(247, 3, 40, 81900, 6, 0, 2, 4),
(248, 3, 41, 86000, 4, 0, 2, 5),
(249, 3, 42, 90200, 5, 0, 2, 7),
(250, 3, 43, 94500, 6, 0, 2, 6),
(251, 3, 44, 98900, 4, 0, 2, 4),
(252, 3, 45, 103400, 5, 0, 2, 5),
(253, 3, 46, 108000, 6, 0, 2, 7),
(254, 3, 47, 112700, 4, 0, 2, 6),
(255, 3, 48, 117500, 5, 0, 2, 4),
(256, 3, 49, 122400, 6, 0, 2, 5),
(257, 3, 50, 127400, 4, 0, 2, 7),
(258, 3, 51, 132500, 5, 0, 2, 6),
(259, 3, 52, 137700, 6, 0, 2, 4),
(260, 3, 53, 143000, 4, 0, 2, 5),
(261, 3, 54, 148400, 5, 0, 2, 7),
(262, 3, 55, 153900, 6, 0, 2, 6),
(263, 3, 56, 159500, 4, 0, 2, 4),
(264, 3, 57, 165200, 5, 0, 2, 5),
(265, 3, 58, 171000, 6, 0, 2, 7),
(266, 3, 59, 176900, 4, 0, 2, 6),
(267, 3, 60, 182900, 5, 0, 2, 4),
(268, 3, 61, 189000, 6, 0, 2, 5),
(269, 3, 62, 195200, 4, 0, 2, 7),
(270, 3, 63, 201500, 5, 0, 2, 6),
(271, 3, 64, 207900, 6, 0, 2, 4),
(272, 3, 65, 214400, 4, 0, 2, 5),
(273, 3, 66, 221000, 5, 0, 2, 7),
(274, 3, 67, 227700, 6, 0, 2, 6),
(275, 3, 68, 234500, 4, 0, 2, 4),
(276, 3, 69, 241400, 5, 0, 2, 5),
(277, 3, 70, 248400, 6, 0, 2, 7),
(278, 3, 71, 255500, 4, 0, 2, 6),
(279, 3, 72, 262700, 5, 0, 2, 4),
(280, 3, 73, 270000, 6, 0, 2, 5),
(281, 3, 74, 277400, 4, 0, 2, 7),
(282, 3, 75, 284900, 5, 0, 2, 6),
(283, 3, 76, 292500, 6, 0, 2, 4),
(284, 3, 77, 300200, 4, 0, 2, 5),
(285, 3, 78, 308000, 5, 0, 2, 7),
(286, 3, 79, 315900, 6, 0, 2, 6),
(287, 3, 80, 323900, 4, 0, 2, 4),
(288, 3, 81, 332000, 5, 0, 2, 5),
(289, 3, 82, 340200, 6, 0, 2, 7),
(290, 3, 83, 348500, 4, 0, 2, 6),
(291, 3, 84, 356900, 5, 0, 2, 4),
(292, 3, 85, 365400, 6, 0, 2, 5),
(293, 3, 86, 374000, 4, 0, 2, 7),
(294, 3, 87, 382700, 5, 0, 2, 6),
(295, 3, 88, 391500, 6, 0, 2, 4),
(296, 3, 89, 400400, 4, 0, 2, 5),
(297, 3, 90, 409400, 5, 0, 2, 7),
(298, 3, 91, 418500, 6, 0, 2, 6),
(299, 3, 92, 427700, 4, 0, 2, 4),
(300, 3, 93, 437000, 5, 0, 2, 5),
(301, 3, 94, 446400, 6, 0, 2, 7),
(302, 3, 95, 455900, 4, 0, 2, 6),
(303, 3, 96, 465500, 5, 0, 2, 4),
(304, 3, 97, 475200, 6, 0, 2, 5),
(305, 3, 98, 485000, 4, 0, 2, 7),
(306, 3, 99, 494900, 5, 0, 2, 6),
(307, 3, 100, 504900, 6, 0, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int NOT NULL,
  `chapter_id` int DEFAULT NULL,
  `next_chapter_id` int DEFAULT NULL,
  `choice_text` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `links`
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
-- Table structure for table `marchand`
--

CREATE TABLE `marchand` (
  `pnj_id` int NOT NULL,
  `shop_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `currency` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'gold'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marchand`
--

INSERT INTO `marchand` (`pnj_id`, `shop_name`, `currency`) VALUES
(2, 'Boutique du Marchand', 'pieces');

-- --------------------------------------------------------

--
-- Table structure for table `marchand_inventory`
--

CREATE TABLE `marchand_inventory` (
  `id` int NOT NULL,
  `marchand_id` int NOT NULL,
  `item_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marchand_inventory`
--

INSERT INTO `marchand_inventory` (`id`, `marchand_id`, `item_id`, `price`, `quantity`) VALUES
(1, 2, 2, 25.00, 10),
(2, 2, 3, 20.00, 10),
(3, 2, 4, 60.00, 10),
(4, 2, 5, 250.00, 10),
(5, 2, 8, 400.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `monster`
--

CREATE TABLE `monster` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `pv` int NOT NULL,
  `mana` int DEFAULT NULL,
  `initiative` int NOT NULL,
  `strength` int NOT NULL,
  `attack` text COLLATE utf8mb4_general_ci,
  `xp` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monster`
--

INSERT INTO `monster` (`id`, `name`, `pv`, `mana`, `initiative`, `strength`, `attack`, `xp`, `image`) VALUES
(1, 'Loup sauvage', 100, 10, 4, 10, 'Le loup sauvage vous mord', 100, '/R3_01-Dungeon-Explorer/sprites/monstres/trashMob/loup/loup1.png'),
(2, 'Sanglier enragé', 150, 0, 6, 6, 'Le sanglier vous charge à toute vitesse', 120, '/R3_01-Dungeon-Explorer/sprites/monstres/trashMob/sanglier/sanglier1.png'),
(3, 'Orc', 200, 50, 100, 30, 'L\'orc vous met un puissant coup de massue.', 500, NULL),
(4, 'Squelette Guerrier', 80, 0, 3, 12, 'Le squelette frappe avec ses os.', 80, '/R3_01-Dungeon-Explorer/sprites/monstres/trashMob/squelette/squeletteGuerrier/squeletteGuerrier1.png'),
(5, 'Garde Fantôme', 120, 20, 6, 15, 'Le fantôme traverse votre armure.', 150, '/R3_01-Dungeon-Explorer/sprites/monstres/trashMob/guerrierFantome.png'),
(6, 'Chevalier Noir', 250, 0, 5, 25, 'Le chevalier abat sa lourde épée.', 300, '/R3_01-Dungeon-Explorer/sprites/monstres/boss/chevalierNoir.png'),
(7, 'Seigneur Nécromancien', 400, 200, 8, 20, 'Le nécromancien lance un sort de mort.', 1000, '/R3_01-Dungeon-Explorer/sprites/monstres/boss/necromancien.png');

-- --------------------------------------------------------

--
-- Table structure for table `monster_loot`
--

CREATE TABLE `monster_loot` (
  `id` int NOT NULL,
  `monster_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `drop_rate` decimal(5,2) DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monster_loot`
--

INSERT INTO `monster_loot` (`id`, `monster_id`, `item_id`, `quantity`, `drop_rate`) VALUES
(1, 1, 1, 10, 50.00),
(2, 4, 1, 5, 30.00),
(3, 5, 3, 1, 25.00),
(4, 6, 5, 1, 10.00),
(5, 7, 9, 1, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `pnj`
--

CREATE TABLE `pnj` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pnj`
--

INSERT INTO `pnj` (`id`, `name`, `description`, `image`) VALUES
(1, 'Aldric le Captif', 'Un ancien aventurier emprisonné ici depuis des lustres.', './sprites/pnj/prisonnier.png'),
(2, 'Marchand itinérant', 'Un marchand qui vend quelques objets utiles.', '/R3_01-Dungeon-Explorer/sprites/pnj/marchand.png');

-- --------------------------------------------------------

--
-- Table structure for table `pnj_dialogue`
--

CREATE TABLE `pnj_dialogue` (
  `id` int NOT NULL,
  `pnj_id` int NOT NULL,
  `sequence` int NOT NULL DEFAULT '0',
  `dialogue` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pnj_dialogue`
--

INSERT INTO `pnj_dialogue` (`id`, `pnj_id`, `sequence`, `dialogue`) VALUES
(1, 1, 0, 'Par les dieux ! Un vivant ?'),
(2, 1, 1, 'Méfiez-vous du Chevalier Noir à l\'étage, son armure est impénétrable par la magie.'),
(3, 1, 2, 'Prenez ceci, je l\'avais caché dans ma botte...');

-- --------------------------------------------------------

--
-- Table structure for table `spell`
--

CREATE TABLE `spell` (
  `id` int NOT NULL,
  `mana_cost` int NOT NULL,
  `damage` int NOT NULL,
  `spell_name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spell`
--

INSERT INTO `spell` (`id`, `mana_cost`, `damage`, `spell_name`, `description`) VALUES
(1, 60, 150, 'Boule de feu', 'Une énorme boule de feu carbonisant votre adversaire.'),
(2, 5, 30, 'Lame magique', 'Vous projetez votre mana pour créer une lame qui fend les airs... et vos adversaires.');

-- --------------------------------------------------------

--
-- Table structure for table `users_aria`
--

CREATE TABLE `users_aria` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_aria`
--

INSERT INTO `users_aria` (`id`, `username`, `email`, `password_hash`, `is_admin`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$iosU4lpjx5hKIijwhtfxguTLslzxluwVTWeQ4AowsUSWc2uQhA8oq', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chapter_treasure`
--
ALTER TABLE `chapter_treasure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chapter_id` (`chapter_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encounter`
--
ALTER TABLE `encounter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `monster_id` (`monster_id`);

--
-- Indexes for table `hero`
--
ALTER TABLE `hero`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `armor_item_id` (`armor_item_id`),
  ADD KEY `primary_weapon_item_id` (`primary_weapon_item_id`),
  ADD KEY `secondary_weapon_item_id` (`secondary_weapon_item_id`),
  ADD KEY `shield_item_id` (`shield_item_id`);

--
-- Indexes for table `hero_progress`
--
ALTER TABLE `hero_progress`
  ADD PRIMARY KEY (`hero_id`,`chapter_id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `hero_spell`
--
ALTER TABLE `hero_spell`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hero_id` (`hero_id`,`spell_id`),
  ADD KEY `spell_id` (`spell_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hero_id` (`hero_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `next_chapter_id` (`next_chapter_id`);

--
-- Indexes for table `marchand`
--
ALTER TABLE `marchand`
  ADD PRIMARY KEY (`pnj_id`);

--
-- Indexes for table `marchand_inventory`
--
ALTER TABLE `marchand_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marchand_id` (`marchand_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `monster`
--
ALTER TABLE `monster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monster_loot`
--
ALTER TABLE `monster_loot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `monster_id` (`monster_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `pnj`
--
ALTER TABLE `pnj`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `pnj_dialogue`
--
ALTER TABLE `pnj_dialogue`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pnj_id` (`pnj_id`,`sequence`);

--
-- Indexes for table `spell`
--
ALTER TABLE `spell`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_aria`
--
ALTER TABLE `users_aria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `chapter_treasure`
--
ALTER TABLE `chapter_treasure`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `encounter`
--
ALTER TABLE `encounter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hero`
--
ALTER TABLE `hero`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `hero_spell`
--
ALTER TABLE `hero_spell`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `marchand_inventory`
--
ALTER TABLE `marchand_inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `monster`
--
ALTER TABLE `monster`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `monster_loot`
--
ALTER TABLE `monster_loot`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pnj`
--
ALTER TABLE `pnj`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pnj_dialogue`
--
ALTER TABLE `pnj_dialogue`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spell`
--
ALTER TABLE `spell`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_aria`
--
ALTER TABLE `users_aria`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chapter_treasure`
--
ALTER TABLE `chapter_treasure`
  ADD CONSTRAINT `chapter_treasure_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `chapter_treasure_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `encounter`
--
ALTER TABLE `encounter`
  ADD CONSTRAINT `encounter_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `encounter_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `monster` (`id`);

--
-- Constraints for table `hero`
--
ALTER TABLE `hero`
  ADD CONSTRAINT `hero_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `hero_ibfk_2` FOREIGN KEY (`armor_item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `hero_ibfk_3` FOREIGN KEY (`primary_weapon_item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `hero_ibfk_4` FOREIGN KEY (`secondary_weapon_item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `hero_ibfk_5` FOREIGN KEY (`shield_item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `hero_progress`
--
ALTER TABLE `hero_progress`
  ADD CONSTRAINT `fk_hero_progress_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `fk_hero_progress_hero` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hero_progress_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`),
  ADD CONSTRAINT `hero_progress_ibfk_2` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`);

--
-- Constraints for table `hero_spell`
--
ALTER TABLE `hero_spell`
  ADD CONSTRAINT `hero_spell_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`),
  ADD CONSTRAINT `hero_spell_ibfk_2` FOREIGN KEY (`spell_id`) REFERENCES `spell` (`id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `level`
--
ALTER TABLE `level`
  ADD CONSTRAINT `level_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`);

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  ADD CONSTRAINT `links_ibfk_2` FOREIGN KEY (`next_chapter_id`) REFERENCES `chapter` (`id`);

--
-- Constraints for table `marchand`
--
ALTER TABLE `marchand`
  ADD CONSTRAINT `marchand_ibfk_1` FOREIGN KEY (`pnj_id`) REFERENCES `pnj` (`id`);

--
-- Constraints for table `marchand_inventory`
--
ALTER TABLE `marchand_inventory`
  ADD CONSTRAINT `marchand_inventory_ibfk_1` FOREIGN KEY (`marchand_id`) REFERENCES `marchand` (`pnj_id`),
  ADD CONSTRAINT `marchand_inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `monster_loot`
--
ALTER TABLE `monster_loot`
  ADD CONSTRAINT `monster_loot_ibfk_1` FOREIGN KEY (`monster_id`) REFERENCES `monster` (`id`),
  ADD CONSTRAINT `monster_loot_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `pnj_dialogue`
--
ALTER TABLE `pnj_dialogue`
  ADD CONSTRAINT `pnj_dialogue_ibfk_1` FOREIGN KEY (`pnj_id`) REFERENCES `pnj` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
