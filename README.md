# CSIHA Test Project

<h2>🖥 Screenshot:</h2>

![screenshot](https://github.com/gergipeter/taskmanager_csiha/blob/main/snapshot.JPG)

<h2>🛠️ Installation Steps:</h2>

## With Docker
```bash
    git clone https://github.com/gergipeter/taskmanager_csiha.git
    cd taskmanager_csiha

    cp .env.example .env
    composer install
    
    ./vendor/bin/sail up
    ./vendor/bin/sail npm i -D
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate:fresh --seed
    ./vendor/bin/sail artisan test
    ./vendor/bin/sail npm run dev
```
## Without Docker
```bash
    git clone https://github.com/gergipeter/taskmanager_csiha.git
    cd taskmanager_csiha

    cp .env.example .env
    composer install
       
    php artisan key:generate
    php artisan migrate
    php artisan test
    php artisan db:seed

    php artisan serve
    npm install
    npm run dev
```


<h2>💻 Built with</h2>

Technologies used in the project:

> **PHP** 8.3.1
> 
> **MySQL** 8.0.35
> 
> **TypeScript** 5.3.3
> 
> **NodeJs** 20.1.0
> 
> **Laravel** 10.40.0
> 
> **Vue** 3.4.7
> 
> **Vite** 5.0.2
> 
> **SQLite** for tests
