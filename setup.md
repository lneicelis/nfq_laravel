## NFQ Gallery

#Requirements:
php >=5.3
GD2
PhpFileInfo

#I Step
In app/config/database.php file set your database parameters.

#II Step
Initiate composer install by running command:
php composer install

#III Step
Run outstanding migrations by commands bellow:
php artisan migrate --package=cartalyst/sentry
php artisan migrate --path=app/database/migrations/install
php artisan db:seed

#IV Step
Now you can register. The first user will have administrator rights.





