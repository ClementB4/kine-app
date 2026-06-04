# Kine App

Application de suivi de rééducation permettant à des kinésithérapeutes de gérer leurs patients, leurs prises en charge, leurs séances et leurs exercices.

## Stack technique

### Backend

* Symfony 7
* PHP 8.3
* Doctrine ORM
* PostgreSQL

### Infrastructure

* Docker
* Docker Compose
* Nginx

## Lancement du projet

```bash
docker compose up -d
```

Application Symfony :

http://localhost:8080

## Architecture métier

### Utilisateurs

* Administrateur
* Kinésithérapeute
* Patient

### Entités principales

* User
* Workplace
* UserWorkplace
* Patient
* PatientCase
* PatientCasePhysio
* Pathology
* PatientCasePathology
* Exercise
* Session
* SessionExercise

### Fonctionnalités prévues

* Gestion des cabinets
* Gestion des kinés
* Gestion des patients
* Gestion des prises en charge
* Gestion des séances
* Bibliothèque d'exercices
* Bilans et tests de reprise
* Espace patient

## État du projet

### Infrastructure

* [x] Docker
* [x] Nginx
* [x] Symfony 7
* [x] PostgreSQL
* [x] Doctrine ORM

### Backend

* [x] Modèle de données
* [x] Migrations Doctrine
* [x] Fixtures de développement
* [ ] Authentification JWT
* [ ] API REST
* [ ] Gestion des permissions

### Fonctionnalités métier

* [x] Modélisation des patients
* [x] Modélisation des prises en charge
* [x] Modélisation des séances
* [x] Modélisation des exercices
* [ ] Gestion des bilans
* [ ] Gestion des programmes
* [ ] Suivi de progression
* [ ] Interface Angular
