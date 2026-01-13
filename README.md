# R3_01-Dungeon-Explorer

## Description

Projet de groupe R3_01 - Dungeon Explorer est une application web développée en PHP qui simule un jeu de rôle d'exploration de donjon. Les utilisateurs peuvent créer des héros, explorer des chapitres, combattre des monstres, gérer leur équipement et interagir avec des marchands. L'application inclut également un panneau d'administration pour gérer les utilisateurs, les monstres, les classes, les chapitres et les liens entre chapitres.

## Fonctionnalités

- **Création de compte et connexion** : Inscription et authentification des utilisateurs.
- **Création de héros** : Choix de classe (guerrier, mage, voleur) avec des sprites personnalisés.
- **Exploration de chapitres** : Navigation à travers différents chapitres du donjon avec des liens conditionnels.
- **Système de combat** : Combats automatiques contre des monstres avec gestion des sorts et équipements.
- **Gestion d'équipement** : Achat et vente d'objets auprès de marchands.
- **Panneau d'administration** : Interface pour les administrateurs pour gérer les entités du jeu (utilisateurs, monstres, classes, chapitres, liens).
- **Interface utilisateur** : Pages dédiées pour la gestion du compte utilisateur.

## Technologies utilisées

- **Backend** : PHP (avec architecture MVC)
- **Base de données** : MySQL
- **Frontend** : HTML, CSS, JavaScript
- **Serveur** : Compatible avec des environnements comme Laragon, XAMPP ou WAMP
- **Autres** : Sprites et images pour les personnages et monstres

## Installation

### Prérequis

- Serveur web avec PHP (version 7.4 ou supérieure)
- MySQL
- Navigateur web moderne

### Étapes d'installation

1. **Cloner ou télécharger le projet** :
   Placez le dossier du projet dans le répertoire racine de votre serveur web (par exemple, `c:\laragon\www\` pour Laragon).

2. **Configurer la base de données** :
   - Importez le fichier `dungeon_explorer.sql` dans votre serveur MySQL.
   - Modifiez les paramètres de connexion dans `Database.php` si nécessaire (hôte, utilisateur, mot de passe, nom de la base).

3. **Configurer l'autoloader** :
   - Assurez-vous que `autoload.php` est correctement configuré pour charger les classes.

4. **Lancer le serveur** :
   - Démarrez votre serveur web (par exemple, via Laragon).
   - Accédez à l'application via `http://localhost/R3_01-Dungeon-Explorer/` ou l'URL appropriée.

5. **Permissions** :
   - Assurez-vous que les dossiers `sprites/` et autres ressources sont accessibles en lecture.

## Utilisation

- **Page d'accueil** : Accédez à `index.php` pour commencer.
- **Inscription/Connexion** : Créez un compte ou connectez-vous.
- **Création de héros** : Après connexion, créez votre héros.
- **Exploration** : Naviguez dans les chapitres via les liens.
- **Combat** : Engagez des combats automatiquement.
- **Administration** : Accédez au panneau admin via les contrôleurs appropriés (nécessite des droits admin).

## Structure du projet

- `controllers/` : Contrôleurs PHP pour gérer la logique métier.
- `models/` : Modèles PHP représentant les entités (Hero, Monster, etc.).
- `views/` : Vues HTML/PHP pour l'interface utilisateur.
- `user/` : Pages spécifiques aux utilisateurs (connexion, gestion compte).
- `admin/` : Interface d'administration.
- `script/` : Fichiers JavaScript.
- `style/` : Fichiers CSS.
- `sprites/` : Images et sprites pour le jeu.
- `dungeon_explorer.sql` : Script SQL pour la base de données.

## Contributeurs

- Projet de groupe R3_01

## Licence

Ce projet est destiné à des fins éducatives. Veuillez consulter les termes d'utilisation si applicable. 
