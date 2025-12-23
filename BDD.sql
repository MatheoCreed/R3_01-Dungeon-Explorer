-- ==================================================================
-- 1. NETTOYAGE DE LA BASE DE DONNÉES
-- ==================================================================
-- On désactive la vérification des clés étrangères pour pouvoir DROP sans erreur
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


-- ==================================================================
-- 2. CRÉATION DES TABLES "PARENTS" (Indépendantes)
-- ==================================================================

-- 1. Utilisateurs (Doit être créé avant Hero)
CREATE TABLE users_aria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, 
    is_admin TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- 2. Classes de personnages
CREATE TABLE Class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    base_pv INT NOT NULL,
    base_mana INT NOT NULL,
    strength INT NOT NULL,
    initiative INT NOT NULL,
    max_items INT NOT NULL,
    image VARCHAR(255)
) ENGINE=InnoDB;

-- 3. Objets (Items)
CREATE TABLE Items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    item_type VARCHAR(50) NOT NULL, -- Ex: 'Arme', 'Armure', 'Potion', etc.
    value INT -- c'est le bonus des équipements, à NULL si autre type d'item
);

-- 4. Monstres
CREATE TABLE Monster (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    pv INT NOT NULL,
    mana INT,
    initiative INT NOT NULL,
    strength INT NOT NULL,
    attack TEXT,
    xp INT NOT NULL,
    image VARCHAR(255)
) ENGINE=InnoDB;

-- 5. Chapitres (Lieux/Histoire)
CREATE TABLE Chapter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) DEFAULT 'Chapitre',
    content TEXT NOT NULL,
    image VARCHAR(255)
) ENGINE=InnoDB;

-- Création de la table Spell (Spells disponibles dans le jeu)
CREATE TABLE Spell (
    id INT AUTO_INCREMENT PRIMARY KEY,
    spell_name VARCHAR(100) NOT NULL,
    mana_cost INT NOT NULL,
    damage INT,
    description TEXT
);

-- Création de la table Hero (Personnage principal)
-- Les équipements (armor, primary_weapon, etc.) font référence à des Items.
CREATE TABLE Hero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(100),
    image VARCHAR(255),
    biography TEXT,
    pv INT NOT NULL,
    mana INT NOT NULL,
    strength INT NOT NULL,
    initiative INT NOT NULL,
    
    armor_item_id INT,
    primary_weapon_item_id INT,
    secondary_weapon_item_id INT,
    shield_item_id INT,
    
    xp INT NOT NULL,
    current_level INT DEFAULT 1,
    
    FOREIGN KEY (class_id) REFERENCES Class(id),
    FOREIGN KEY (armor_item_id) REFERENCES Items(id),
    FOREIGN KEY (primary_weapon_item_id) REFERENCES Items(id),
    FOREIGN KEY (secondary_weapon_item_id) REFERENCES Items(id),
    FOREIGN KEY (shield_item_id) REFERENCES Items(id)
);

-- Table intermédiaire pour les spells des héros (Hero - Spell)
CREATE TABLE Hero_Spell (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT NOT NULL,
    spell_id INT NOT NULL,
    FOREIGN KEY (hero_id) REFERENCES Hero(id),
    FOREIGN KEY (spell_id) REFERENCES Spell(id),
    UNIQUE (hero_id, spell_id) -- Un héros ne peut apprendre un spell qu'une fois
);

-- Création de la table Level (Niveaux de progression des classes)
CREATE TABLE Level (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    level INT NOT NULL,
    required_xp INT NOT NULL,
    pv_bonus INT NOT NULL,
    mana_bonus INT NOT NULL,
    strength_bonus INT NOT NULL,
    initiative_bonus INT NOT NULL,
    FOREIGN KEY (class_id) REFERENCES Class(id)
) ENGINE=InnoDB;

-- 8. Butin des monstres (Dépend de Monster et Items)
CREATE TABLE Monster_Loot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monster_id INT,
    item_id INT,
    quantity INT NOT NULL DEFAULT 1,
    drop_rate DECIMAL(5, 2) DEFAULT 1.0,
    FOREIGN KEY (monster_id) REFERENCES Monster(id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (monster_id, item_id)
) ENGINE=InnoDB;

-- 9. Dialogues PNJ (Dépend de PNJ)
CREATE TABLE PNJ_Dialogue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pnj_id INT NOT NULL,
    sequence INT NOT NULL DEFAULT 0,
    dialogue TEXT NOT NULL,
    FOREIGN KEY (pnj_id) REFERENCES PNJ(id),
    UNIQUE (pnj_id, sequence)
) ENGINE=InnoDB;

-- 10. Marchands (Extension de PNJ)
CREATE TABLE Marchand (
    pnj_id INT PRIMARY KEY,
    shop_name VARCHAR(100),
    currency VARCHAR(50) DEFAULT 'gold',
    FOREIGN KEY (pnj_id) REFERENCES PNJ(id)
) ENGINE=InnoDB;

-- 11. Trésors de chapitre (Dépend de Chapter et Items)
CREATE TABLE Chapter_Treasure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    item_id INT,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (chapter_id, item_id)
) ENGINE=InnoDB;

-- 12. Rencontres (Dépend de Chapter et Monster)
CREATE TABLE Encounter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    monster_id INT,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id),
    FOREIGN KEY (monster_id) REFERENCES Monster(id)
) ENGINE=InnoDB;

-- 13. Liens entre chapitres (Dépend de Chapter)
CREATE TABLE Links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    next_chapter_id INT,
    choice_text TEXT,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id),
    FOREIGN KEY (next_chapter_id) REFERENCES Chapter(id)
) ENGINE=InnoDB;


-- ==================================================================
-- 4. CRÉATION DES TABLES COMPLEXES (Niveau 2 - Héros et Inventaires)
-- ==================================================================

-- 14. Inventaire Marchand (Dépend de Marchand et Items)
CREATE TABLE Marchand_Inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marchand_id INT NOT NULL,
    item_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (marchand_id) REFERENCES Marchand(pnj_id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (marchand_id, item_id)
) ENGINE=InnoDB;

-- 15. HÉROS (Dépend de users_aria, Class et Items)
CREATE TABLE Hero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,   -- Doit matcher users_aria(id)
    name VARCHAR(50) NOT NULL,
    class_id INT,
    image VARCHAR(255),
    biography TEXT,
    pv INT NOT NULL,
    mana INT NOT NULL,
    strength INT NOT NULL,
    initiative INT NOT NULL,
    
    -- Équipements (Peuvent être NULL si pas équipé)
    armor_item_id INT,
    primary_weapon_item_id INT,
    secondary_weapon_item_id INT,
    shield_item_id INT,
    
    spell_list TEXT,
    xp INT NOT NULL DEFAULT 0,
    current_level INT DEFAULT 1,

    -- Définition des clés étrangères
    CONSTRAINT fk_hero_user FOREIGN KEY (user_id) REFERENCES users_aria(id) ON DELETE CASCADE,
    CONSTRAINT fk_hero_class FOREIGN KEY (class_id) REFERENCES Class(id),
    CONSTRAINT fk_hero_armor FOREIGN KEY (armor_item_id) REFERENCES Items(id),
    CONSTRAINT fk_hero_weapon1 FOREIGN KEY (primary_weapon_item_id) REFERENCES Items(id),
    CONSTRAINT fk_hero_weapon2 FOREIGN KEY (secondary_weapon_item_id) REFERENCES Items(id),
    CONSTRAINT fk_hero_shield FOREIGN KEY (shield_item_id) REFERENCES Items(id)
) ENGINE=InnoDB;

-- 16. Inventaire du Héros (Dépend de Hero et Items)
CREATE TABLE Inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT,
    item_id INT,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (hero_id) REFERENCES Hero(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (hero_id, item_id)
) ENGINE=InnoDB;

-- 17. Progression du Héros (Dépend de Hero et Chapter)
CREATE TABLE Hero_Progress (
    hero_id INT,
    chapter_id INT,
    PRIMARY KEY (hero_id, chapter_id),

    FOREIGN KEY (hero_id) REFERENCES Hero(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id)
) ENGINE=InnoDB;



INSERT INTO class
(id, name, description, base_pv, base_mana, strength, initiative, max_items, image) 
VALUES
(1, 'Guerrier', 'Un combattant robuste maîtrisant les armes lourdes.', 120, 30, 15, 5, 3, '/R3_01-Dungeon-Explorer/sprites/joueur/guerrierMale/epeeBouclier/guerrierMaleEpeeBouclierFace1.png'),

(2, 'Mage', 'Un utilisateur de magie puissant mais fragile.', 70, 120, 5, 8, 5, '/R3_01-Dungeon-Explorer/sprites/joueur/mageMale/mageMaleFace1.png'),

(3, 'Voleur', 'Un combattant agile spécialisé dans la vitesse et les attaques précises.', 90, 50, 10, 15, 4, '/R3_01-Dungeon-Explorer/sprites/joueur/voleur/voleurFace1.png');

