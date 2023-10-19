## Overview 

This project is written to implement a refactor of a database schema design. The implementation is done with Laravel framework. Its an implementation of a database design and does not require authentication. 
## Documentation Note: 
The postman documentation for all the API endpoints can be found on this url
- [Get Postman Endpoints Documentation](https://documenter.getpostman.com/view/13952977/2s9YR9ZtDL)

## Installation & Usage

### Downloading the Project

This project requires PHP 8.1 and mysql database
.  
You can simply clone  ``db-schema-restructure` like below on your git bash

```bash
git clone https://github.com/angwa/db-schema-restructure.git
```
After cloning the project, please run this command on the project directory
```
composer install
```
### Configure Environment
To run the application you must configure the ```.env``` environment file with your database configurations. Use the following commmand to create .env file. 
```
cp .env.example .env

```
You can check the default .env.example if you want to manually create the .env file

The keys to be updated in your .env file that was just generated in the above command is

```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Please replace the above env variables pointing to your database

### Generating  Application key
Run the following command on the project directory to generate a new app key

```
php artisan key:generate
```

## Note
If you run into error, run the following commands one after the other on the project directory
``` 
composer update
php artisan optimize
```

## Testing

To run test test, type the following on the project directory

``` bash
php artisan test
```

## Seeding DB
Once your database is correctly installed, you can seed your database by running
```
php artisan db:seed
```

## Security

If you discover any security related issues, please email angwamoses@gmail.com instead of using the issue tracker.

## Credits

- [Angwa Moses](https://github.com/angwa)


