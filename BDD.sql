-- Création de la table Class (Classe des personnages)
CREATE TABLE Class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    base_pv INT NOT NULL,
    base_mana INT NOT NULL,
    strength INT NOT NULL,
    initiative INT NOT NULL,
    max_items INT NOT NULL
    image VARCHAR(255);
);

-- Création de la table Items (Objets disponibles dans le jeu)
CREATE TABLE Items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    item_type VARCHAR(50) NOT NULL -- Ex: 'Arme', 'Armure', 'Potion', etc.
);


-- Création de la table Monster (Monstres rencontrés dans l'histoire)
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
    -- loot_id supprimé
);

-- Table intermédiaire pour les butins des monstres (Monster - Items)
-- Permet à un monstre de lâcher plusieurs types d'objets, avec une quantité.
CREATE TABLE Monster_Loot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monster_id INT,
    item_id INT,
    quantity INT NOT NULL DEFAULT 1,
    drop_rate DECIMAL(5, 2) DEFAULT 1.0, -- Taux de chance de drop (ex: 0.5 pour 50%)
    FOREIGN KEY (monster_id) REFERENCES Monster(id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (monster_id, item_id) -- Un seul type d'objet par monstre dans cette table
);

-- Création de la table Hero (Personnage principal)
-- Les équipements (armor, primary_weapon, etc.) font référence à des Items.
CREATE TABLE Hero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    class_id INT, -- Relation avec Class
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
    
    spell_list TEXT,
    xp INT NOT NULL,
    current_level INT DEFAULT 1,
    
    FOREIGN KEY (class_id) REFERENCES Class(id),
    FOREIGN KEY (armor_item_id) REFERENCES Items(id),
    FOREIGN KEY (primary_weapon_item_id) REFERENCES Items(id),
    FOREIGN KEY (secondary_weapon_item_id) REFERENCES Items(id),
    FOREIGN KEY (shield_item_id) REFERENCES Items(id)
);

-- Création de la table Level (Niveaux de progression des classes)
CREATE TABLE Level (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT, -- Relation avec Class
    level INT NOT NULL,
    required_xp INT NOT NULL,
    pv_bonus INT NOT NULL,
    mana_bonus INT NOT NULL,
    strength_bonus INT NOT NULL,
    initiative_bonus INT NOT NULL,
    FOREIGN KEY (class_id) REFERENCES Class(id)
);


-- Création de la table Chapter (Chapitres de l'histoire)
CREATE TABLE Chapter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    image VARCHAR(255)
);

-- Création de la table PNJ (Personnages non-joueurs)
CREATE TABLE PNJ (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(100),
    image VARCHAR(255),
    UNIQUE (name)
);

-- Table pour stocker une liste ordonnée de dialogues pour chaque PNJ
CREATE TABLE PNJ_Dialogue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pnj_id INT NOT NULL,
    sequence INT NOT NULL DEFAULT 0,
    dialogue TEXT NOT NULL,
    FOREIGN KEY (pnj_id) REFERENCES PNJ(id),
    UNIQUE (pnj_id, sequence)
);

-- Marchand table: one-to-one extension of PNJ (a marchand is a PNJ)
CREATE TABLE Marchand (
    pnj_id INT PRIMARY KEY,
    shop_name VARCHAR(100),
    currency VARCHAR(50) DEFAULT 'gold',
    FOREIGN KEY (pnj_id) REFERENCES PNJ(id)
);

-- Marchand inventory: which items a marchand sells, with price and quantity
CREATE TABLE Marchand_Inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marchand_id INT NOT NULL,
    item_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (marchand_id) REFERENCES Marchand(pnj_id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (marchand_id, item_id)
);

-- Table intermédiaire pour les trésors dans les chapitres (Chapter - Items)
CREATE TABLE Chapter_Treasure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    item_id INT,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (chapter_id, item_id) -- Un seul type d'objet par chapitre dans cette table
);

-- Création de la table Encounter (Rencontres dans les chapitres)
CREATE TABLE Encounter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    monster_id INT,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id),
    FOREIGN KEY (monster_id) REFERENCES Monster(id)
);

-- Table intermédiaire pour l'inventaire des héros (Hero - Items)
CREATE TABLE Inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT,
    item_id INT,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (hero_id) REFERENCES Hero(id),
    FOREIGN KEY (item_id) REFERENCES Items(id),
    UNIQUE (hero_id, item_id) -- Un seul enregistrement par type d'objet par héros
);

-- Création de la table Links (Liens entre chapitres)
CREATE TABLE Links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    next_chapter_id INT,
    description TEXT,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id),
    FOREIGN KEY (next_chapter_id) REFERENCES Chapter(id)
);

-- Table intermédiaire pour le suivi de progression (Hero - Chapter)
CREATE TABLE Hero_Progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT,
    chapter_id INT,
    status VARCHAR(20) DEFAULT 'Completed', -- Ex: 'Started', 'Completed', 'Failed'
    completion_date DATETIME, -- Pour marquer quand le chapitre a été terminé
    FOREIGN KEY (hero_id) REFERENCES Hero(id),
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id)
);
