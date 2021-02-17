P6-SnowTricks

Codacy Badge

Création d'un site communautaire de partage de figures de snowboard via le framework Symfony.
Environnement utilisé durant le développement

    Symfony 5.1.*
    Composer 1.11
    Bootswatch 4.5.2
    WampServer 3.2.3
        Apache 2.4.46
        PHP 7.3.21
        MySQL 5.7.31
    cocur/slugify 4.0
    fzaninotto/faker" 1.9
    twig/extra-bundle": 2.12 à 3.0
    twig/twig" 2.12 à 3.0

Installation

    - Clonez ou téléchargez le repository GitHub dans le dossier voulu :

    git clone https://github.com/sorha/P6-SnowTricks.git

    - Configurez vos variables d'environnement tel que la connexion à la base de données ou votre serveur SMTP ou adresse mail dans le fichier .env.local 
    qui devra être crée à la racine du projet en réalisant une copie du fichier .env.

    - Téléchargez et installez les dépendances back-end du projet avec Composer :

    composer install

    - Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :

    php bin/console doctrine:database:create

    - Créez les différentes tables de la base de données en appliquant les migrations :

    php bin/console doctrine:migrations:migrate

    - Installer les fixtures pour avoir une démo de données fictives :

    php bin/console doctrine:fixtures:load

    - Félications le projet est installé correctement, vous pouvez désormais commencer à l'utiliser à votre guise !
