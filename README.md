# school-managing-system

## Commands 

composer create-project symfony/skeleton school-managing-system <br />
composer require annotations <br />
composer require twig <br />
composer require doctrine maker <br />
php bin/console doctrine:database:create <br />
php bin/console make:entity Student <br />    
php bin/console make:entity Course <br />     
php bin/console make:entity Classes <br /> 
php bin/console make:entity Grade <br />      
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity <br />
php bin/console make:entity --regenerate App <br />
composer require form <br />
composer require knplabs/knp-paginator-bundle <br />
composer require sonata-project/admin-bundle <br />
composer require sonata-project/doctrine-orm-admin-bundle
