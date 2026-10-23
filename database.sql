CREATE DATABASE IF NOT EXISTS concessionnaire CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE concessionnaire;

CREATE TABLE IF NOT EXISTS voitures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voiture_id INT NOT NULL,
    nom_client VARCHAR(100) NOT NULL,
    email_client VARCHAR(100) NOT NULL,
    statut_paiement VARCHAR(20) DEFAULT 'En attente',
    stripe_id_session VARCHAR(255),
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insertion de données de test
INSERT INTO voitures (marque, modele, annee, prix, image, description) VALUES
('Audi', 'RS6 Avant', 2024, 145000.00, 'https://unsplash.com', 'Break de chasse ultra-sportif de 600 chevaux, parfait état.'),
('Porsche', '911 Carrera GTS', 2023, 162000.00, 'https://unsplash.com', 'Icône sportive par excellence. Version GTS optimisée pour circuit et route.'),
('Tesla', 'Model S Plaid', 2024, 109000.00, 'https://unsplash.com', 'Berline électrique surpuissante, 1020 chevaux, autonomie de 600km.');
