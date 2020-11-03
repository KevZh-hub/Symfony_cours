Faire attention a bien avoir la version 7 de php ! (mettez à jour pour etre sûr)

Créer un nouveau projet symfony : symfony new nomduprojet --full    

Se mettre dans le bon dossier, puis : php bin/console make:entity NomDuEntity

Mettre un nom pour la propriété (titre, categorie, description etc..)

Créer une database (demarrez le serveur xampp): php bin/console doctrine:database:create

puis faire une migration : php bin/console make:migration

Faire égelament : php bin/console doctrine:migrations:migrate

symfony server:start

