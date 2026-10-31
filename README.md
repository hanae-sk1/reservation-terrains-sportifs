# Réservation de terrains sportifs

Application web simple permettant de réserver un terrain (tennis, padel, football, basketball) et de consulter la liste des réservations.

## Technologies

- HTML / CSS
- JavaScript (validation côté client)
- PHP (mysqli, requêtes préparées)
- MySQL / MariaDB

## Fonctionnalités

- Formulaire de réservation (nom, téléphone, terrain, date, heure, durée)
- Validation côté client (JavaScript) et côté serveur (PHP)
- Refus des dates passées et des créneaux déjà occupés sur un même terrain
- Liste des réservations triée par date et heure


## Structure

```
├── includes/config.php   # connexion à la base
├── reservation.html      # formulaire
├── script.js             # validation côté client
├── traitement.php        # validation serveur + enregistrement
├── liste.php             # affichage des réservations
├── style.css
└── sport_db.sql          # création de la base et de la table
```
