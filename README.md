# 🌱 Wood Wiccan - Système de Gestion de Contenu Écologique

## 📋 Table des matières

- [À propos](#à-propos)
- [Solution développée](#solution-développée)
- [Prérequis](#prérequis)
- [Installation](#installation)

## 🌍 À propos

Wood Wiccan est un système de gestion de contenu conçu avec une approche éco-responsable. Il privilégie les performances énergétiques, minimise l'empreinte carbone numérique et encourage les bonnes pratiques environnementales dans le développement web.

### Mission

L'objectif est de mettre sur le marché un CMS éco-conçu, d'essaimer le principe de l'éco-conception et d'intégrer le cercle vertueux des nouveaux outils responsables. 

## 🌿 Solution développée

- **Minimisation drastique du nombre requêtes en BDD** : Le but de la technologie est de proposer nativement une récupération de données calibrées à leur utilisation. 
- **Minimisation du nombre requêtes HTTP** : Le calibrage de l'ensemble des données récupérées doit se faire via configuration et combler au maximum les besoins en affichage du site.
- **Multisite natif** : Wood Wiccan est basé sur une gestion d'arborescence infinie, ce qui permet facilement d'avoir une gestion multisite native.
- **Technologies unvireselles** : Une aproche inspirée des mouvements "low tech" fait que Wood Wiccan est complètement développé en PHP / MySQL (en plus des technologies HTML/JS/CSS liées aux browsers) dans le but de se prendre facilement en main.
- **Valeurs d'inclusivité** : Les concepts développés dans Wood Wiccan se déclinent autour de la figure de la sorcière afin de créer un univers ludique, essayant de s'éloigner de l'utilisation massive des sigles et acronymes, tout en affirmant des valeurs inclusives.

## 🛠 Prérequis

- **PHP** : Version 8.1 ou supérieure
- **Base de données** : MySQL / MariaDB
- **Composer** : Pour la gestion des dépendances PHP
- **Docker** : Pour implémenter localement 


## 🚀 Installation

Pour une instalation locale il faut au préalable installer Docker sur votre machine : 
https://docs.docker.com/get-started/get-docker/

=> Si vous souhaitez contribuer au projet, il vous faudra avoir installer GIT sur votre machine : 
https://git-scm.com/downloads


Ensuite il faut rapatrier le code depuis le répository GitHub :
https://github.com/Jean2Grom/woodwiccan-local/tree/release/0.0.2
depuis cette page, cliquez sur le bouton "<> Code", puis sur le lien "Download ZIP"; décompresser ensuite le fichier archive dans votre répertoire de travail.

=> Si vous souhaitez contribuer au projet, il vous faut cloner le projet dans votre répertoire à la place du télechargement décrit ci dessus. Depuis une invite de commande, navigez jusqu'à votre répertoire de travail, puis : 

```bash
# Cloner le repository
git clone https://github.com/Jean2Grom/woodwiccan-local.git

# Se placer sur la bonne branche
git fetch origin release/0.0.2
git checkout release/rc-0.0.2

# Créer sa branche de travail
git checkout -b feat/my-contribution
```

Se placer ensuite dans le répertoire du projet Wood Wiccan ainsi récupéré avec une invite de commande, puis :

```bash
docker-compose up
```

Une fois l'installation de docker-compose terminée, se rendre depuis votre navigateur sur l'URL http://localhost:9990/ et constater un message de "System down Please contact administrator"; se rendre ensuite à l'adresse http://localhost:9991, où un serveur local PHPMyAdmin vous attends, pour se connecter il faut entrer "woodwiccan" comme nom d'utilisateur, et "ChangeUserPassword" en mot de passe (le mot de passe est le contenu du fichier db-user-password.txt). 

Depuis PHPMyAdmin, cliquez sur la base de donnée "woodwiccan", puis sur "Importer", puis sur "Parcourir" dans la partie "Fichier à importer", choisissez le fichier "woodwiccan.sql" à la racine du projet, puis cliuez sur "Importer" en bas de page.

C'est fini ! 
vous pouvez désormais vous rendre sur les URLs : 
http://localhost:9990 pour un contenu vierge avec le logo de Wood Wiccan
http://localhost:9990/example pour un afficher un site d'exemple
http://localhost:9990/admin pour accéder au backoffice, pour se conncter c'est "admin" en nom d'utilisateur, et "ChangeUserPassword" en mot de passe
http://localhost:9990/admin-demo pour accéder a un backoffice plus restrein, dédié au site d'exemple.


Voilà, n'hésitez pas à me contacter

jean2grom