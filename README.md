# Améliorez une application existante de ToDo & Co - Symfony - P8 OCR

## Informations :

-  Identifiants:

```
"username": "admin"
"password": "12345"
```

## Installation :

-  **1.** Cloner le projet

```
git clone https://github.com/Tony-marques/P8_Todo-Co.git
```

-  **2.** Installer les dépendances `composer install` à la racine du projet

-  **3.** Créer un fichier .env à la racine du projet avec les identifiants de votre base de donnée

-  **4.** Créer la base de donnée

```
symfony console doctrine:database:create
```

-  **5.** Créer les tables dans la base de donnée

```
symfony console doctrine:migrations:migrate
```

-  **6.** Créer les données avec les fixtures symfony

```
symfony console doctrine:fixtures:load
```
