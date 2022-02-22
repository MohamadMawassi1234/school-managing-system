# school-managing-system

## Commands 

composer create-project symfony/skeleton school-managing-system
composer require annotations
composer require twig 
composer require doctrine maker
php bin/console doctrine:database:create
php bin/console make:entity Student     
php bin/console make:entity Course      
php bin/console make:entity Classes  
php bin/console make:entity Grade       
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity
php bin/console make:entity --regenerate App
composer require form
composer require knplabs/knp-paginator-bundle
composer require sonata-project/admin-bundle
composer require sonata-project/doctrine-orm-admin-bundle
