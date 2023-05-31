## HOW TO CLONE AND USE 

1. Clone the repo
```bash
git clone https://github.com/christmex/basic-teacher-book-loan.git
```
2. Copy env
```bash
cp .env.example .env
```
3. Set the database and set the database name on env
4. Change the APP_URL to your domain or localhost etc
5. Composer install
```bash
composer install
```
6. Key generate
```bash
php artisan key:generate
```
7. Link storage
```bash
php artisan storage:link
```
8. Migrate and Seed
```bash
php artisan migrate --seed
```


