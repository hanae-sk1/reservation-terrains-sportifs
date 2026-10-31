-- Base de données du projet "Réservation de terrains sportifs"
-- Import : phpMyAdmin > Importer, ou : mysql -u root -p < sport_db.sql

CREATE DATABASE IF NOT EXISTS sport_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE sport_db;

CREATE TABLE IF NOT EXISTS reservations (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(100) NOT NULL,
    telephone   CHAR(10)     NOT NULL,
    terrain     ENUM('tennis', 'padel', 'football', 'basketball') NOT NULL,
    date_res    DATE         NOT NULL,
    heure_debut TIME         NOT NULL,
    duree       TINYINT UNSIGNED NOT NULL COMMENT 'Durée en heures (1 à 3)',
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_creneau (terrain, date_res, heure_debut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
