-- ============================================
-- DarLoc - Base de données
-- ============================================
CREATE DATABASE IF NOT EXISTS darloc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE darloc;

-- Table 1 : utilisateurs (admin + client)
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS maisons;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','client') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table 2 : maisons
CREATE TABLE maisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    type ENUM('S+1','S+2','S+3','S+4','Villa','Duplex') NOT NULL,
    ville VARCHAR(100) NOT NULL,
    quartier VARCHAR(150) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    prix INT NOT NULL,
    surface INT NOT NULL,
    pieces INT NOT NULL,
    sdb INT NOT NULL DEFAULT 1,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table 3 : réservations (liée à users + maisons)
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    maison_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_attente','confirmee','annulee') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (maison_id) REFERENCES maisons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Données de test
-- ============================================

-- Admin (mot de passe: admin123)
INSERT INTO users (nom, email, password, role) VALUES
('Administrateur', 'admin@darloc.tn', '$2y$10$YourHashWillBeReplacedOnFirstLogin', 'admin');

-- Note: pour générer un vrai hash, lancer login.php une fois ou utiliser:
-- UPDATE users SET password = '<?php echo password_hash("admin123", PASSWORD_DEFAULT); ?>' WHERE email='admin@darloc.tn';

-- Maisons d'exemple
INSERT INTO maisons (titre, type, ville, quartier, adresse, prix, surface, pieces, sdb, description, image, disponible) VALUES
('Appartement lumineux centre-ville', 'S+2', 'Tunis', 'Lac 2', 'Rue du Lac Toba, Lac 2', 1400, 95, 3, 1, 'Magnifique appartement S+2 entièrement rénové, baigné de lumière naturelle, à deux pas des commerces et restaurants du Lac 2.', 'house-1.jpg', 1),
('Villa de prestige avec piscine', 'Villa', 'Hammamet', 'Yasmine Hammamet', 'Avenue des Hôtels, Yasmine', 4500, 320, 6, 4, 'Villa de standing avec piscine privée, jardin paysager et vue dégagée. Idéale pour familles ou séjours d''exception.', 'house-2.jpg', 1),
('Studio cosy proche plage', 'S+1', 'Sousse', 'Khezama', 'Avenue Habib Bourguiba, Khezama', 650, 42, 2, 1, 'Studio chaleureux et fonctionnel, parfait pour célibataire ou couple. À 5 minutes à pied de la plage.', 'house-3.jpg', 1),
('Spacieux appartement familial', 'S+3', 'Sfax', 'Route El Ain', 'Route El Ain km 4, Sfax', 1800, 145, 4, 2, 'Grand appartement familial avec balcon, séjour double, cuisine américaine et trois chambres confortables.', 'house-4.jpg', 0),
('Villa pieds dans l''eau', 'Villa', 'Djerba', 'Midoun', 'Plage Sidi Mahrez, Midoun', 3200, 240, 5, 3, 'Villa exceptionnelle en bord de mer avec accès direct à la plage. Couchers de soleil inoubliables.', 'house-5.jpg', 1),
('Duplex moderne et design', 'Duplex', 'Tunis', 'La Marsa', 'Rue Mongi Slim, La Marsa', 2400, 180, 4, 2, 'Duplex contemporain avec mezzanine, hauteur sous plafond et finitions haut de gamme dans un quartier résidentiel calme.', 'house-6.jpg', 1);
